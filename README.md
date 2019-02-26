# lu.snj.matricule

Checks whether CiviCRM custom fields labeled 'Matricule' contain a valid Luxembourg national identity number.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.6+
* CiviCRM 5.10+

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/grischard/lu.snj.matricule.git
cv en matricule
```

## Usage

Create a custom field with the label 'Matricule'.

## Known Issues

* The label of the custom field is hard coded.
