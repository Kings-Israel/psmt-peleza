var addComment = function(user,comment,mysql,pool,callback) {
    var self = this;
    pool.getConnection(function(err,connection){
        if (err) {
            connection.release();
            return callback(true,null);
        } else {
            var sqlQuery = "INSERT into ?? (??,??) VALUES (?,?)";
            var inserts =["notification","name",
                "comment",user,"A new request has been made"];

            sqlQuery = mysql.format(sqlQuery,inserts);

            connection.query(sqlQuery,function(err,rows){
                connection.release();
                if (err) {
                    return callback(true,null);
                } else {
                    callback(false,"comment added");
                }
            });
        }
        connection.on('error', function(err) {
            return callback(true,null);
        });
    });
};

module.exports.addComment = addComment;