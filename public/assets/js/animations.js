/** @format */

// ============================================
// SISTEMA GLOBAL DE NOTIFICACIONES Y CONFIRMACIONES
// ============================================
const SystemUI = {
  modal: null,
  resolvePromise: null,

  init() {
    this.modal = document.getElementById('sys-confirm-modal');
    if (!this.modal) return;

    this.cacheElements();
    this.bindEvents();
    this.exposeAPI();
  },

  cacheElements() {
    this.msgEl = this.modal.querySelector('.sys-modal-msg');
    this.okBtn = this.modal.querySelector('.sys-btn-ok');
    this.cancelBtn = this.modal.querySelector('.sys-btn-cancel');
    this.iconEl = this.modal.querySelector('.sys-modal-icon');
  },

  bindEvents() {
    this.okBtn?.addEventListener('click', () => this.close(true));
    this.cancelBtn?.addEventListener('click', () => this.close(false));

    this.modal.addEventListener('click', (e) => {
      if (e.target === this.modal) this.close(false);
    });

    // Interceptación global de [data-confirm]
    document.addEventListener('click', async (e) => {
      const trigger = e.target.closest('[data-confirm]');
      if (!trigger) return;

      e.preventDefault();
      const message = trigger.dataset.confirm || '¿Estás seguro?';
      const type = trigger.dataset.type || 'confirm'; // confirm, delete, warning
      const confirmed = await this.show(message, type);

      if (!confirmed) return;

      // Ejecutar acción
      this.executeAction(trigger);
    });
  },

  async show(message, type = 'confirm') {
    return new Promise((resolve) => {
      this.msgEl.textContent = message;
      this.setType(type);

      this.modal.style.display = 'flex';
      requestAnimationFrame(() => this.modal.classList.add('active'));

      this.resolvePromise = resolve;
    });
  },

  showNotification(message, type = 'success') {
    // Para notificaciones informativas (sin cancelar)
    return this.show(message, type);
  },

  setType(type) {
    // Remover clases previas
    this.modal.classList.remove(
      'type-confirm',
      'type-delete',
      'type-warning',
      'type-success',
      'type-error',
    );
    this.modal.classList.add(`type-${type}`);

    // Icono dinámico
    if (this.iconEl) {
      const icons = {
        confirm: 'fas fa-question-circle',
        delete: 'fas fa-exclamation-triangle',
        warning: 'fas fa-exclamation-circle',
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
      };
      this.iconEl.className = `sys-modal-icon ${icons[type] || icons.confirm}`;
    }
  },

  close(result) {
    this.modal.classList.remove('active');
    setTimeout(() => {
      this.modal.style.display = 'none';
    }, 200);

    if (this.resolvePromise) {
      this.resolvePromise(result);
      this.resolvePromise = null;
    }
  },

  executeAction(trigger) {
    const form = trigger.closest('form');
    const href = trigger.href;
    const ajax = trigger.dataset.ajax === 'true';

    if (form) {
      if (ajax) {
        this.submitAjax(form, trigger);
      } else {
        form.submit();
      }
      return;
    }

    if (href) {
      if (ajax) {
        this.fetchAjax(href, trigger);
      } else {
        window.location.href = href;
      }
    }
  },

  async submitAjax(form, trigger) {
    const submitBtn = form.querySelector('[type="submit"]');
    const originalText = submitBtn?.innerHTML;

    try {
      if (submitBtn) {
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        submitBtn.disabled = true;
      }

      const formData = new FormData(form);
      const response = await fetch(form.action, {
        method: form.method || 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });

      const result = await response.json();

      if (result.success) {
        await this.showNotification(
          result.message || '¡Operación exitosa!',
          'success',
        );
        if (result.redirect) window.location.href = result.redirect;
        else if (result.reload) location.reload();
      } else {
        await this.showNotification(
          result.message || 'Error al procesar',
          'error',
        );
      }
    } catch (error) {
      await this.showNotification('Error de conexión con el servidor', 'error');
      console.error('AJAX Error:', error);
    } finally {
      if (submitBtn && originalText) {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    }
  },

  async fetchAjax(url, trigger) {
    try {
      const response = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });
      const result = await response.json();

      if (result.success) {
        await this.showNotification(
          result.message || '¡Completado!',
          'success',
        );
        if (result.redirect) window.location.href = result.redirect;
        else if (result.reload) location.reload();
      } else {
        await this.showNotification(result.message || 'Error', 'error');
      }
    } catch (error) {
      await this.showNotification('Error de conexión', 'error');
    }
  },

  exposeAPI() {
    // Funciones globales para usar desde cualquier lugar
    window.sysConfirm = (msg) => this.show(msg, 'confirm');
    window.sysAlert = (msg, type = 'success') =>
      this.showNotification(msg, type);
    window.sysDelete = (msg) => this.show(msg, 'delete');
  },
};

// ============================================
// CLASE DE ANIMACIONES (Tu código original optimizado)
// ============================================
class TravelAnimations {
  constructor() {
    this.initAnimations();
    this.initScrollAnimations();
    this.initParallax();
    this.initCounters();
  }

  initAnimations() {
    this.animateOnScroll();
    this.typewriterEffect();
    this.animateNumbers();
  }

  initScrollAnimations() {
    const navbar = document.querySelector('.navbar');

    const onScroll = () => {
      if (window.scrollY > 100) {
        navbar?.classList.add('scrolled');
      } else {
        navbar?.classList.remove('scrolled');
      }

      // Reveal elements
      document.querySelectorAll('.reveal').forEach((element) => {
        if (element.getBoundingClientRect().top < window.innerHeight - 100) {
          element.classList.add('revealed');
        }
      });
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // Initial check
  }

  initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax');
    if (!parallaxElements.length) return;

    window.addEventListener(
      'scroll',
      () => {
        const scrolled = window.pageYOffset;
        parallaxElements.forEach((element) => {
          const speed = element.dataset.speed || 0.5;
          element.style.transform = `translateY(${scrolled * speed}px)`;
        });
      },
      { passive: true },
    );
  }

  initCounters() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const counter = entry.target;
            const target = parseInt(counter.dataset.target);
            const duration = parseInt(counter.dataset.duration) || 2000;
            this.animateCounter(counter, target, duration);
            observer.unobserve(counter);
          }
        });
      },
      { threshold: 0.5 },
    );

    document.querySelectorAll('.counter').forEach((el) => observer.observe(el));
  }

  animateCounter(element, target, duration) {
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
      start += increment;
      if (start >= target) {
        element.textContent = target.toLocaleString();
        clearInterval(timer);
      } else {
        element.textContent = Math.floor(start).toLocaleString();
      }
    }, 16);
  }

  animateOnScroll() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animated');
          }
        });
      },
      { threshold: 0.1 },
    );

    document
      .querySelectorAll('.animate-on-scroll')
      .forEach((el) => observer.observe(el));
  }

  typewriterEffect() {
    document.querySelectorAll('.typewriter').forEach((element) => {
      const text = element.textContent;
      element.textContent = '';
      let i = 0;

      const type = () => {
        if (i < text.length) {
          element.textContent += text.charAt(i);
          i++;
          setTimeout(type, 100);
        }
      };

      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          type();
          observer.disconnect();
        }
      });
      observer.observe(element);
    });
  }

  animateNumbers() {
    const observer = new IntersectionObserver((entries) => {
      if (!entries[0].isIntersecting) return;

      const element = entries[0].target;
      const finalValue = parseFloat(element.dataset.value);
      const duration = parseInt(element.dataset.duration) || 2000;
      let startTime = null;

      const animate = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        const percentage = Math.min(progress / duration, 1);
        const currentValue = finalValue * percentage;

        element.textContent = Math.floor(currentValue).toLocaleString();

        if (percentage < 1) {
          requestAnimationFrame(animate);
        } else {
          element.textContent = finalValue.toLocaleString();
        }
      };

      requestAnimationFrame(animate);
      observer.disconnect();
    });

    document
      .querySelectorAll('.animate-number')
      .forEach((el) => observer.observe(el));
  }
}

// ============================================
// INICIALIZACIÓN GLOBAL (ÚNICO DOMContentLoaded)
// ============================================
document.addEventListener('DOMContentLoaded', () => {
  // 1. Inicializar sistema de UI (modales/notificaciones)
  SystemUI.init();

  // 2. Inicializar animaciones
  new TravelAnimations();

  // 3. Smooth scroll para anchors (una sola vez)
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // 4. Formulario de booking con AJAX integrado
  const bookingForm = document.getElementById('bookingForm');
  if (bookingForm) {
    bookingForm.addEventListener('submit', async function (e) {
      e.preventDefault();
      const submitBtn = this.querySelector('[type="submit"]');
      const originalText = submitBtn.innerHTML;

      try {
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin me-2"></i> Procesando...';
        submitBtn.disabled = true;

        const formData = new FormData(this);
        const response = await fetch(this.action, {
          method: this.method || 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });

        const result = await response.json();

        if (result.success) {
          await SystemUI.showNotification(
            result.message || '¡Reserva exitosa!',
            'success',
          );
          if (result.redirect) window.location.href = result.redirect;
          else this.reset();
        } else {
          await SystemUI.showNotification(
            result.message || 'Error en la reserva',
            'error',
          );
        }
      } catch (error) {
        await SystemUI.showNotification('Error de conexión', 'error');
        console.error('Booking error:', error);
      } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    });
  }

  // 5. Actualizar año en footer
  const yearEl = document.querySelector('.current-year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();
});
