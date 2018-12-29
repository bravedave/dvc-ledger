#### DVC Ledger

This is a simple ledger built using the DVC-Auth template

#### Brief
The Ledger is a single transaction file, debits are held as positive and credits as negative.

There are three journal types, each adhering to the rules above, but presented in a
numerically appealing way. Payments for instance present the bank credit,
and Receipts the incomes, as +ve (positive) numbers but they are applied to the ledger
as -ve.

#### Running this demo
1. Creates a SQLite3 database
2. Populates it with basic data
3. **DOES NOT** lock down the system
   * but if you select settings > lockdown and save
     * you will require a username/password to gain access
     * default user/pass = **admin** / **admin**

#### Install
To use DVC on a Windows 10 computer (Devel Environment)
1. Install PreRequisits
   * Install PHP : http://windows.php.net/download/
      * Install the non threadsafe binary
      * by default there is no php.ini (required)
        * copy php.ini-production to php.ini
        * edit and modify/add (uncomment)
          * extension=fileinfo
          * extension=sqlite3
          * extension=mbstring
          * extension=openssl
      * *note these instructions for PHP Version 7.2.7, the older syntax included .dll on windows*

   * Install Git : https://git-scm.com/
     * Install the *Git Bash Here* option
   * Install Composer : https://getcomposer.org/

1. Clone or download this repo
   * Start the *Git Bash* Shell
     * Composer seems to work best here, depending on how you installed Git
   * MD C:\Data\ && CD C:\Data
   * clone:
     * git clone https://github.com/bravedave/dvc-ledger
   * or download as zip and extract
     * https://github.com/bravedave/dvc-ledger/archive/master.zip
   * or setup as new project
     * `composer create-project --repository='{"type":"vcs","url":"https://github.com/bravedave/dvc-ledger"}' bravedave/dvc-ledger my-ledger @dev`

1. optionally change the name and change to the folder
   * cd my-ledger
1. run *composer install*

#### To run the demo
   * Review the run.cmd
     * The program is now accessible: http://localhost
     * Run this from the command prompt to see any errors - there may be a firewall
       conflict options to fix would be - use another port e.g. 8080
