jobinterview-homework
=====================

Repo for assorted Job Interview Homework assignments / doodles

=======

## Company

 * http://www.marketo.com/

Please send us a link to a svn/git repo or a tar containing the php code performing the tasks described below: 

### Program task: 

- fetches an object in JSON format from an endpoint given as an argument 

- stores the object in a mysql db (mysql:host=localhost;dbname=testdb;, user: user, password: passwd) 

### Working assumptions : 

- in production, the script will be run of crond on a minute frequency 

- object JSON format: 

```
	{"name":"",
	 "id":0,
	 "value":"",
	 "timestamp":""}
```
	
where id and name are unique.