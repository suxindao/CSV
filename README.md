# CSV 面试题

### CSV文件的规则如下：
    1、每一行数据包含多个字段,字段间以[,]分割.
    2、如果字段值不含有[,]和["]，直接解析输出.
    3、如果字段值内部含有逗号[,]，在在字段值两边加上双引号["]将字段值括起来.
    4、如果字段值内部含有双引号["]，则字段值两边加上双引号["]括起来,而字段值内的一个双引号["]替换为两个双引号[""].

### 考试要求如下

读入文件cvs.txt，根据上述csv文件的规则进行解析，重新格式化字段生成输出文件output.txt

    将
    第一列转为整形(int)
    第二列为字符串型
    第三列为字符串型
    第四列转为浮点数（float）
    第五列转为日期类型（DateTime）
    输出文件的字段以制表符[TAB]来分割字段，
    字符串字段输出时用单引号[']括起来
    日期字段显示成YYYY/MM/DD的格式


### 说明

    1、可以假设字段值只包含单行数据，即字段值本身不含有[回车换行]
    2、不能对文件csv.txt作任何修改


### 编程要求

    使用任何你熟悉的编程语言编写，时间为1.5小时
