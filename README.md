# Login/Regionstation web service for mobile applications
Simple Login/Registration web service (PHP) made for mobile application

### Requirements 
Setup database configuration in `/include/config.php`


### Login Request
- Method: `POST`
- Parameters : `email`, `password`
- Response : `[error, user]`, `[error, error_msg]`


### Registration Request
- Method: `POST`
- Parameters : `first_name`, `last_name`, `email`, `password`
- Response : `[error, user]`, `[error, error_msg]`


### Update profile Request
- Method: `POST`
- Parameters : `update`, `email_user`, `new_value`
- Response : `[error, user]`, `[error, error_msg]`
