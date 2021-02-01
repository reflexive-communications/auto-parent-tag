# auto-parent-tag

If a child tag is added to a contact, the parent tag will be added automatically.
This is done recursively until a top-tag is reached. (No more parents)

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.2+
* CiviCRM (5.25) probably work below, not tested

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl hu.es-progress.auto-parent-tag@https://github.com/semseysandor/hu.es-progress.auto-parent-tag/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/semseysandor/hu.es-progress.auto-parent-tag.git
cv en auto_parent_tag
```

## Usage

When installed parent tags will be added automatically.
