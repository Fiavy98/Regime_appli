document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-imc-form]');
  if (!form) {
    return;
  }

  const result = document.querySelector('[data-imc-result]');
  const status = document.querySelector('[data-imc-status]');

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const taille = parseFloat(form.querySelector('[name="taille"]').value);
    const poids = parseFloat(form.querySelector('[name="poids"]').value);

    if (!taille || !poids || taille <= 0 || poids <= 0) {
      result.textContent = '---';
      status.textContent = 'Valeurs invalides';
      return;
    }

    const imc = poids / (taille * taille);
    const imcRounded = Math.round(imc * 10) / 10;
    let label = 'Normal';

    if (imc < 18.5) {
      label = 'Maigre';
    } else if (imc < 25) {
      label = 'Normal';
    } else if (imc < 30) {
      label = 'Surpoids';
    } else {
      label = 'Obesite';
    }

    result.textContent = imcRounded.toString();
    status.textContent = label;
  });
});
