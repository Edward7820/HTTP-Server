# HTTP-Server
## Environment Setup
This instruction is for Ubuntu 22.04
```shell
sudo ln -s /etc/freeradius/3.0/mods-available/sqlcounter /etc/freeradius/3.0/mods-enabled/sqlcounter
```
Then, open sqlcounter config file
```shell
sudo vim /etc/freeradius/3.0/mods-available/sqlcounter
```
and add the following lines:
```shell
sqlcounter dailytrafficcounter {
        sql_module_instance = sql
        dialect = ${modules.sql.dialect}

        counter_name = Daily-Traffic
        check_name = Max-Daily-Traffic
        reply_name = Daily-Traffic-Limit
        key = User-Name
        reset  = daily

        query = "SELECT SUM(acctinputoctets + acctoutputoctets) \
                FROM radacct WHERE username = '%{${key}}' \
                AND UNIX_TIMESTAMP(acctstarttime) > '%%b'"
}
```
To enable FreeRadius to use dailycounter and dailytrafficcounter, we have to modify three files: /etc/freeradius/3.0/sites-enabled/default, /etc/freeradius/3.0/radiusd.conf and /etc/freeradius/3.0/dictionary.
For the first file, run the command
```shell /etc/freeradius/3.0/sites-enabled/default
sudo vim 
```
then add dailycounter and dailytrafficcounter under the authorize section as below
```shell
authorize {
    ...
    dailycounter
    dailytrafficcounter
    ...
}
```
For the second file, run the following command
```shell
sudo vim /etc/freeradius/3.0/radiusd.conf
```
and add the dailycounter and dailytrafficcounter under the section instantiate
```shell
instantiate {
    ...
    dailycounter
    dailytrafficcounter
    ...
}
```
For the third file, run the command
```shell
sudo vim etc/freeradius/3.0/dictionary
```
and add a attribute Daily-Traffic-Limit
```shell
...
ATTRIBUTE       Daily-Traffic-Limit     3005    integer64
```