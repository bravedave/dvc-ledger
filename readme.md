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
