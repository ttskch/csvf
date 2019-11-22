# csvf

CLI command to change delimiter, encode, newline code of CSV file.

## Installation

```
$ git clone git@github.com:ttskch/csvf.git
$ cd csvf
$ composer install --no-dev
$ ln -s $(pwd)/bin/csvf /usr/local/bin/csvf
```

## Usage

```
$ csvf with --delimiter=TAB --encoding=UTF-8 --newline=LF /path/to/input.csv
Written into /path/to/input_out.csv
```
