const socialreasonInput = document.getElementById('particulier-option');
const companyInput = document.getElementById('register_form_company');

socialreasonInput.addEventListener('change', () => {
    if (socialreasonInput.value === 'entreprise') {
        companyInput.disabled = false;
    } else {
        companyInput.disabled = true;
    }
});