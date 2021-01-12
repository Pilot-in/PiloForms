# PiloForms 🖊
**Repository containing various reusable ACFE Forms**

---

## To add a new form:
- **Create a folder** named on what your form does.  
- Add those files inside:
  - **`README.md`**  
*(Describe briefly what it does)*  

  - **`form-actions.php`**  
*(Form actions / hooks)*  

  - **`field-group.json`**  
*(ACF form field group)*  

  - **`acfe-form.json`**  
*(ACFE form export)*  

## To import a form:
- Go to **ACF > Tools**
- Import **Field group** first *(`field-group.json`)*
- Import **ACFE Form** next *(`acfe-form.json`)*
- _(optional)_ Include `form-actions.php` file in your PHP code.
