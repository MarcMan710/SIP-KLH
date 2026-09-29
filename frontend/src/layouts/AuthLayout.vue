<template>
  <div class="auth-layout">
    <div class="auth-shell">
      <div class="auth-branding">
        <div class="brand-mark">SIP-KLH</div>
        <h1>Document Eligibility Application</h1>
        <p>Permohonan dokumen lingkungan hidup</p>
      </div>

      <div class="auth-panel">
        <slot />
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-layout {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: var(--spacing-6);
  background: linear-gradient(135deg, #f0fdfa 0%, #eff6ff 100%);
}

.auth-shell {
  width: min(1100px, 100%);
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  background: var(--color-bg-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-2xl);
  box-shadow: var(--shadow-xl);
  overflow: hidden;
}

.auth-branding {
  padding: var(--spacing-10);
  background: linear-gradient(160deg, var(--color-primary) 0%, #0f766e 100%);
  color: var(--color-text-inverse);
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.brand-mark {
  display: inline-flex;
  width: fit-content;
  background: rgba(255, 255, 255, 0.12);
  padding: var(--spacing-2) var(--spacing-3);
  border-radius: var(--radius-full);
  font-weight: 700;
  letter-spacing: 0.04em;
  margin-bottom: var(--spacing-4);
}

.auth-branding h1 {
  color: var(--color-text-inverse);
  font-size: clamp(2rem, 3vw, 2.5rem);
  margin-bottom: var(--spacing-3);
}

.auth-branding p {
  color: rgba(255, 255, 255, 0.8);
  font-size: 1rem;
}

.auth-panel {
  padding: var(--spacing-8);
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (max-width: 768px) {
  .auth-shell {
    grid-template-columns: 1fr;
  }

  .auth-branding {
    padding: var(--spacing-6);
  }

  .auth-panel {
    padding: var(--spacing-6);
  }
}
</style>
