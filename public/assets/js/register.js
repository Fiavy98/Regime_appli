const endpoints = window.NutriPlanRegister || {
  checkEmail: '/user/check_email',
  step1: '/register/step1',
  step2: '/register/step2',
};

const clearErrors = (form) => {
  form.querySelectorAll('[data-error]').forEach((el) => {
    el.textContent = '';
  });
};

const showErrors = (form, errors) => {
  Object.keys(errors || {}).forEach((key) => {
    const target = form.querySelector(`[data-error="${key}"]`);
    if (target) {
      target.textContent = errors[key];
    }
  });
};

const checkEmail = async (email) => {
  if (!email) {
    return null;
  }

  const response = await fetch(endpoints.checkEmail, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ email }),
  });

  return response.json();
};

const step1Form = document.querySelector('#register-step1');
if (step1Form) {
  const emailInput = step1Form.querySelector('input[name="email"]');
  const passwordInput = step1Form.querySelector('input[name="password"]');
  const confirmInput = step1Form.querySelector('input[name="password_confirm"]');

  emailInput.addEventListener('blur', async () => {
    const result = await checkEmail(emailInput.value.trim());
    if (result && !result.disponible) {
      showErrors(step1Form, { email: 'Email deja utilise.' });
    }
  });

  const validatePasswords = () => {
    if (passwordInput.value.length >= 8 && confirmInput.value.length >= 8) {
      if (passwordInput.value !== confirmInput.value) {
        showErrors(step1Form, { password_confirm: 'Confirmation differente.' });
      }
    }
  };

  passwordInput.addEventListener('input', validatePasswords);
  confirmInput.addEventListener('input', validatePasswords);

  step1Form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearErrors(step1Form);

    const response = await fetch(endpoints.step1, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(new FormData(step1Form)),
    });

    const result = await response.json();
    if (!result.success) {
      showErrors(step1Form, result.errors);
      return;
    }

    window.location.href = result.redirect;
  });
}

const step2Form = document.querySelector('#register-step2');
if (step2Form) {
  step2Form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearErrors(step2Form);

    const response = await fetch(endpoints.step2, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(new FormData(step2Form)),
    });

    const result = await response.json();
    if (!result.success) {
      showErrors(step2Form, result.errors);
      return;
    }

    window.location.href = result.redirect;
  });
}
