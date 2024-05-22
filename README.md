# sa-id-number-validation-php
South African ID number Validation PHP

A PHP Trait with South African ID number validation.

# Usage
```
use App\Validations\SaIdNumberVerification;

class Yourclass {
use SaIdNumberVerification;

  public function validateId($idNumber) {
       if ($this->validateSouthAfricanId($idNumber){
            echo "ID number valid!";
      }
  }

}
```

Don't forget to update the namespace.
