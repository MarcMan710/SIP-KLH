<template>
	<div class="uploader">
		<div class="uploader__dropzone">
			<input
				ref="fileInput"
				type="file"
				:accept="acceptedExtensions"
				multiple
				@change="handleChange"
			/>
			<div class="uploader__content">
				<strong>Unggah Dokumen</strong>
				<p>Format yang didukung: PDF, DOC, DOCX, JPG, PNG. Maksimal 10 MB per file.</p>
				<AppButton type="button" variant="outline" @click="triggerInput">Pilih File</AppButton>
			</div>
		</div>

		<ul v-if="selectedFiles.length" class="file-list">
			<li v-for="(file, index) in selectedFiles" :key="index">
				<span>{{ file.name }}</span>
				<small>{{ formatSize(file.size) }}</small>
			</li>
		</ul>

		<p v-if="error" class="error-message">{{ error }}</p>
	</div>
</template>

<script setup>
import { ref } from 'vue'
import AppButton from '@/components/common/AppButton.vue'
import { ALLOWED_EXTENSIONS, MAX_FILE_SIZE_MB } from '@/utils/constants'

const emit = defineEmits(['files-selected', 'upload'])

const fileInput = ref(null)
const selectedFiles = ref([])
const error = ref('')

const acceptedExtensions = `.${ALLOWED_EXTENSIONS.join(',.')}`

function triggerInput() {
	fileInput.value?.click()
}

function handleChange(event) {
	const files = Array.from(event.target.files || [])
	const validFiles = []

	error.value = ''

	for (const file of files) {
		const extension = file.name.split('.').pop()?.toLowerCase()
		const isAllowed = ALLOWED_EXTENSIONS.includes(extension)
		const isTooLarge = file.size > MAX_FILE_SIZE_MB * 1024 * 1024

		if (!isAllowed) {
			error.value = `Format file ${file.name} tidak didukung.`
			continue
		}

		if (isTooLarge) {
			error.value = `Ukuran file ${file.name} melebihi ${MAX_FILE_SIZE_MB} MB.`
			continue
		}

		validFiles.push(file)
	}

	selectedFiles.value = validFiles
	emit('files-selected', validFiles)

	if (validFiles.length) {
		emit('upload', validFiles)
	}

	event.target.value = ''
}

function formatSize(size) {
	if (!size) return '0 KB'
	if (size < 1024) return `${size} B`
	if (size < 1024 * 1024) return `${(size / 1024).toFixed(1)} KB`
	return `${(size / (1024 * 1024)).toFixed(1)} MB`
}
</script>

<style scoped>
.uploader {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-4);
}

.uploader__dropzone {
	border: 1px dashed var(--color-border);
	border-radius: var(--radius-lg);
	background: var(--color-bg-subtle);
	padding: var(--spacing-5);
}

.uploader__dropzone input {
	display: none;
}

.uploader__content {
	display: flex;
	flex-direction: column;
	gap: var(--spacing-2);
	align-items: flex-start;
}

.uploader__content p {
	margin: 0;
	color: var(--color-text-muted);
}

.file-list {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: var(--spacing-2);
}

.file-list li {
	display: flex;
	justify-content: space-between;
	gap: var(--spacing-3);
	background: var(--color-bg-surface);
	border: 1px solid var(--color-border);
	border-radius: var(--radius-md);
	padding: var(--spacing-3);
}

.file-list small {
	color: var(--color-text-muted);
}

.error-message {
	margin: 0;
	color: var(--color-danger);
}
</style>
<!--
Provide document upload functionality.

Support:
- File selection.
- Document type selection.
- File-size validation on the frontend.
- File-format validation on the frontend.
- Upload progress if supported.
- Upload success/error feedback.

The backend remains the authoritative source
for file validation and authorization.

Do not expose internal server filesystem paths.
-->