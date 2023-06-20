//inscription
const particulierInput = document.getElementById( "register_form_socialreason_0");
const entrepriseInput = document.getElementById("register_form_socialreason_1");
const companyInput = document.getElementById("register_form_company");

const toggleCompanyInput = () => {
  companyInput.disabled = !entrepriseInput.checked;
};

particulierInput.addEventListener("change", toggleCompanyInput);
entrepriseInput.addEventListener("change", toggleCompanyInput);

toggleCompanyInput();


