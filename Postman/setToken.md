### Automatically set a Bearer Token for Postman requests?.
```
var res = pm.response.json();
pm.environment.set('token', res.token);
```
##### Example - 
![alt text](images/postman.png "step 4")