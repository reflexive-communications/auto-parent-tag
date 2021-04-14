# auto-parent-tag

![CI](https://github.com/reflexive-communications/auto-parent-tag/workflows/CI/badge.svg)

If a child tag is added to a contact, the parent tag will be added automatically. This is done recursively until a
top-tag is reached. (No more parents)

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.3+
* CiviCRM (5.25) probably work below, not tested
* [RC-Base](https://github.com/reflexive-communications/rc-base) v0.7.0+

## Installation

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and install it
with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/reflexive-communications/auto-parent-tag
cv en auto_parent_tag
```

## Usage

When installed parent tags will be added automatically for contacts.
