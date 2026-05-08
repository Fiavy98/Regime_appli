document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-imc-form]');
  if (!form) {
    return;
  }

  const result = document.querySelector('[data-imc-result]');
  const status = document.querySelector('[data-imc-status]');
  const resultCard = document.querySelector('[data-imc-result-card]');

  const setBadge = (label) => {
    if (!status || !resultCard) {
      return;
    }

    resultCard.classList.remove('imc-card-maigre', 'imc-card-normal', 'imc-card-surpoids', 'imc-card-obesite');
    status.classList.remove('imc-badge-neutral', 'imc-badge-maigre', 'imc-badge-normal', 'imc-badge-surpoids', 'imc-badge-obesite');

    let className = 'imc-badge-neutral';
    if (label === 'Maigre') {
      className = 'imc-badge-maigre';
      resultCard.classList.add('imc-card-maigre');
    } else if (label === 'Normal') {
      className = 'imc-badge-normal';
      resultCard.classList.add('imc-card-normal');
    } else if (label === 'Surpoids') {
      className = 'imc-badge-surpoids';
      resultCard.classList.add('imc-card-surpoids');
    } else if (label === 'Obesite') {
      className = 'imc-badge-obesite';
      resultCard.classList.add('imc-card-obesite');
    }

    status.classList.add(className);
    status.textContent = label;
  };

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    const taille = parseFloat(form.querySelector('[name="taille"]').value);
    const poids = parseFloat(form.querySelector('[name="poids"]').value);

    if (!taille || !poids || taille <= 0 || poids <= 0) {
      result.textContent = '---';
      setBadge('Valeurs invalides');
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
    setBadge(label);
  });
});
