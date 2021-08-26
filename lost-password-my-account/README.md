## Plugin for dashboard with e-commerce without payment

### Helpers functions to get page selectors data

### Option page

- Simple pages
    - Login page (Page selector)
    - Register page (Page selector)
    - Dashboard page (Page selector)
    - Lost password page (Page selector)
- E-commerce pages
    - Shop page (Page selector)
    - Cart page (Page selector)
    - Checkout page (Page selector)
    - Thank you page (Page selector)
- Configuration
    - Dashboard menu (Menu selector - _pip-addon field_)
    - Where to redirect user when not logged in? (Page selector)
- Emails
    - Lost password - Step 1
        - Object (Text field)
        - Message with `{change_pwd_url}` shortcode (WYSIWYG Editor)
    - Lost password - Step 2
        - Object (Text field)
        - Message (WYSIWYG Editor)

### Page template

- Check if user is connected else redirect him to specific page

### Hooks

- `pma/template_dir`: choose where to search template files
- `pma/templates`: template files list
- `pma/allowed_urls`: list of URLs in the dashboard, to avoid redirection
- `pma/dashboard_bypass_redirect`: boolean to avoid redirection
- `pma/options_id`: options page ID
- `pma/paths/options`: options field group path
- `pma/paths/change_password`: change password field group path
- `pma/paths/lost_password_1`: lost password step 1 field group path
- `pma/paths/lost_password_2`: lost password step 2 field group path

### Available features

#### Shortcode for menu item URL

- `#logout_url#`: will be replace by logout URL with nounce and redirection to home page

#### Change password form feature

- Fields : current password, new password, new password confirmation
- Check if user exists
- Check if current password if correct
- Check if passwords are similar
- Change password on submit

#### Lost password form feature

**Step 1**

- Fields : email
- Check if user exists
- Add reset key in transient for 1h
- Send email with link to step 2

**Step 2**

- Fields : new password, new password confirmation, user id (hidden)
- Check if reset key in URL and transient are the same
- Check if passwords are similar
- Change password on submit
- Send confirmation email
- Clean transient