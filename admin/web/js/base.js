


/**
 * 获取指定日期的时间戳
 *
 * var newDate = new Date();
 * console.log(newDate.timestamp('2012-01-02 12:02:02'));
 *
 * @param time
 * @returns {number}
 */
Date.prototype.timestamp = function(time){
    var timestamp = 0;
    if(time == ''){
        timestamp = Date.parse(new Date());
    }else{
        timestamp = Date.parse(new Date(time));
    }
    return timestamp / 1000;
};
/**
 * 获取指定时间戳的格式化日期
 *
 * var newDate = new Date();
 * console.log(newDate.format('1325476922', 'yyyy-MM-dd hh:mm:ss'));
 *
 * @param timestamp
 * @param format 格式化后的样式  yyyy-MM-dd hh:mm:ss
 * @returns {*}
 */
Date.prototype.format = function(timestamp, format) {
    console.log(timestamp);
    if(timestamp != '' && timestamp != 0){
        this.setTime(timestamp * 1000);
    }else{
        return '--';
    }
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
};