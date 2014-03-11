upout-interview
===============

RESTful PHP interface for interview with UpOut.com





Just enough ElasticSearch 

Download the latest version of elasticsearch from http://www.elasticsearch.org
Elasticsearch runs on top of java so make sure you have this installed. I’ve run elasticsearch successfully on OS X 10.7/10.8/10.9 as well as RHEL/CentOS and Ubuntu without any issue, all that should be required is java.
Navigate to the bin folder and run
./elasticsearch -f
The -f command will keep elasticsearch running in the foreground

You should now have an instance of elasticsearch running! You’re all set to start indexing and querying data

The two things you need to know about before indexing data are indexes and mappings

An index is the base data separation definition provided by elasticsearch. They’re much more complex than that but for now that’s all that we’ll get into.

Mappings are a second layer of data separation, and are defined inside indexes. 

An elasticsearch node can have any number of indexes, and an index can have any number of mappings 

When creating documents, you create them inside of a mapping. As such a mapping can define a set of properties for its documents.

More details about mappings and the properties they can define here: http://www.elasticsearch.org/guide/reference/mapping

Here’s an example of creating an index, creating a mapping within that index, and indexing some data. These examples are designed specifically for this project, and all you should have to do is paste the commands in a bash shell. Ensure CURL is installed and ElasticSearch is running.:

Create Index:
https://gist.github.com/willtrking/1499b3dc70ca77db2a5c

Creating mapping:
https://gist.github.com/willtrking/426094c8cd9048c5b8e7

Index data:
https://gist.github.com/willtrking/5161e2e36ca89f33036d
