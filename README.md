ClusterServerMonitor-for-lab
============================

# HPC Cluster Server Monitor System for laboratory.  

## API
#### URL <ROOT_URL>/:API/:method/:parameters

* Server List API 
    * API : list
    * method : show

* Status API
    * API : status
    * method : show
        * parameter : <server_name>

* Free Disk Space API
    * API : df
    * method : show
        * parameter : <server_name>
    
* Input/Output Status API
    * API : io
    * method : show
        * parameter : <server_name>
    
* TOP API
    * API : top
    * method : show
        * parameter : <server_name>
    
* Status History API
    * API : history
    * method : show
        * parameter : <date> ex. 20130101
    
* Observer API
    * API : observer
    * method : show
    
* Authentication API
    * API : auth
    * method : show
    
* Alert API
    * API : alert
    * method : add
        * parameter : <server_name>/<pid>/<mail>/<command>/<commuser>
    * method : del
        * parameter : <server_name>/<pid>/<mail>/<rand>
    * method : show
    
* Signup API
    * API : signup
    * method : authentication
    * post : <hashed_password> and <CSRF_token>
    
    
    
    
