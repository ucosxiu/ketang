import Config from '../etc/config'

var X = function () {
  var i = ["[微笑]", "[嘻嘻]", "[哈哈]", "[可爱]", "[可怜]", "[挖鼻]", "[吃惊]", "[害羞]", "[挤眼]", "[闭嘴]", "[鄙视]", "[爱你]", "[泪]", "[偷笑]", "[亲亲]", "[生病]", "[太开心]", "[白眼]", "[右哼哼]", "[左哼哼]", "[嘘]", "[衰]", "[委屈]", "[吐]", "[哈欠]", "[抱抱]", "[怒]", "[疑问]", "[馋嘴]", "[拜拜]", "[思考]", "[汗]", "[困]", "[睡]", "[钱]", "[失望]", "[酷]", "[色]", "[哼]", "[鼓掌]", "[晕]", "[悲伤]", "[抓狂]", "[黑线]", "[阴险]", "[怒骂]", "[互粉]", "[心]", "[伤心]", "[猪头]", "[熊猫]", "[兔子]", "[ok]", "[耶]", "[good]", "[NO]", "[赞]", "[来]", "[弱]", "[草泥马]", "[神马]", "[囧]", "[浮云]", "[给力]", "[围观]", "[威武]", "[奥特曼]", "[礼物]", "[钟]", "[话筒]", "[蜡烛]", "[蛋糕]"],
    a = {};
  return i.forEach(function (e, i) {
    a[e] = Config.fileBasePath + "/static/client/webim/images/face/" + i + ".gif"
  }), a
}()

class HttpParser {
  constructor() {
  }

  html2json(html) {
    var result = [];
    (html || "").replace(/&(?!#?[a-zA-Z0-9]+;)/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/'/g, "&#39;").replace(/"/g, "&quot;").replace(/@(\S+)(\s+?|$)/g, '@<a href="javascript:;">$1</a>$2').replace(/face\[([^\s\[\]]+?)\]/g, function (i) {
      return '^||||$' + i + '^||||$'
    }).replace(/img\[([^\s]+?)\]/g, function (i) {
      return '^||||$' + i + '^||||$'
    }).replace(/file\([\s\S]+?\)\[[\s\S]*?\]/g, function (i) {
      return '^||||$' + i + '^||||$'
    }).split('^||||$').forEach(function (value) {
      if (value != '') {
        if (value.match(/face\[([^\s\[\]]+?)\]/g)) {
          //face
          var a = value.replace(/^face/g, "");
          var src = X[a];

          result.push({
            'node': 'face',
            'src': src,
            'content': value
          })

        } else if (value.match(/img\[([^\s]+?)\]/g)) {
          //img
          var a = value.replace(/(^img\[)|(\]$)/g, ""),
            src = Config.fileBasePath + a;
          result.push({
            'node': 'img',
            'src': src,
            'content': value
          })

        } else if (value.match(/file\([\s\S]+?\)\[[\s\S]*?\]/g)) {
          //file
          var a = (value.match(/file\(([\s\S]+?)\)\[/) || [])[1],
            e = (value.match(/\)\[([\s\S]*?)\]/) || [])[1],
          src = Config.fileBasePath + a;
          if (/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(e)) {
            result.push({
              'node': 'img',
              'src': src,
              'content': value
            })
          } else {
            result.push({
              'node': 'file',
              'src': src,
              'name': e,
              'content': value
            })
          }
        } else {
          //text
          result.push({
            'node': 'text',
            'content': value
          })
        }
      }
    })

    return result;
  }
}

export default HttpParser