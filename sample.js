// 加载File System读写模块  
const fs = require('fs');

let readStream = fs.createReadStream(__dirname + '/csv.txt', {encoding: 'utf8'});
let writeStream = fs.createWriteStream(__dirname + '/output_js.txt', {encoding: 'utf8'});

let dataArray = []

const status_normal = 0
const status_quot = 1

// 1,Jane,"下棋,""飞""",56.2,1976-8-23
// 2,Kate,购物,49.6,1979-12-56
// 3,Jerry,"羽毛球,爬山",55.6,1980-5-26
// 4,brian,"aaa,"",""bbb",22222
// 5,"ccc,",ddd,eee
// 6,""",""",888

readStream.on('data', function (chunk) { // 当有数据流出时，写入数据

        let index = 0, start = 0, status = status_normal, str
        while (index < chunk.length) {
            let char = chunk[index]

            switch (char) {
                case "\"":
                    if (status == status_quot) {
                        if (chunk[index + 1] == "\"") { //在引号读取状态中，碰到连续两个引号("")则前进一位，继续读取
                            index++
                        } else if (chunk[index + 1] == ",") { //在引号读取状态中，碰到引号逗号(",)则跳出引号状态
                            status = status_normal
                        }
                    } else { // 更改状态为引号读取状态
                        status = status_quot
                    }
                    break
                case ",":
                    if (status != status_quot) {
                        //如果不在引号读取状态，则拆分string
                        str = chunk.substring(start, index)

                        //去掉首尾引号
                        str = (str.indexOf("\"") != -1) ? str.substring(1, str.length - 1) : str

                        //替换双引号为单引号
                        str = str.replace(/""/g, "\"")

                        //写入数组
                        dataArray.push(str)

                        //重置状态
                        status = status_normal

                        //start 变更到当前下一位，准备继续截取 string
                        start = index + 1
                    }
                    break
                case "\n":
                    //读到本行结尾截取字符串
                    str = chunk.substring(start, index)
                    //去掉首尾引号
                    str = (str.indexOf("\"") != -1) ? str.substring(1, str.length - 1) : str
                    //替换双引号为单引号
                    str = str.replace(/""/g, "\"")
                    //写入数组
                    dataArray.push(str)
                    //重置状态
                    status = status_normal
                    //start 变更到当前下一位，准备继续截取 string
                    start = index + 1

                    //往数组中添加换行标志
                    dataArray.push("\n")
                default:
                    break
            }

            index++;
        }

        //查看数组里面的数据
        dataArray.forEach(item => {
            console.log(item)
        })

        //将除了 "\n" 的元素后面添加 tab 并拼接成字符串
        let newStr = dataArray.map(item => {
            if (item !== "\n")
                return item + " "
            else {
                return item
            }
        }).join("")

        //输出到新文件中
        if (writeStream.write(newStr) === false) { // 如果没有写完，暂停读取流
            readStream.pause();
        }
    }
);

writeStream.on('drain', function () { // 写完后，继续读取
    readStream.resume();
});

readStream.on('end', function () { // 当没有数据时，关闭数据流
    writeStream.end();
});


