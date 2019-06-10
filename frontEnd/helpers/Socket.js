import EventEmitter from 'EventEmitter'

const App = getApp();
const emitter = new EventEmitter()
let settings = {
  maxReconnectAttempts: 10,
  reconnectInterval: 1000
}

class Socket{
  constructor(url) {
    this.url = url;
    this.emitter = emitter;

    this.init()
  }

  init() {
    for (var key in settings) {
      this[key] = settings[key];
    }
  }

  setToken(token) {
    this.token = token;
  }
  
  //微信链接
  connect(reconnectAttempt) {
    var self = this;
    this.socketTask = wx.connectSocket({
      url: this.url+'?token=' + this.token,
      success: function () {
        self.reconnectAttempts = 0;
      }
    })

    this.socketTask.onMessage(function(res){
      self.emitter.emit('onSocketMessage', res)
    })

    this.socketTask.onError(function(res){
      console.log('onError')
    })

    this.socketTask.onClose(function(res){
      console.log('onClose')
      setTimeout(function () {
        self.reconnectAttempts++;
        self.connect(true);
      }, self.reconnectInterval)
    })

    // wx.onSocketMessage(function (res){
    //   self.emitter.emit('onSocketMessage', res)
    // });

    // wx.onSocketError(function (res) { // 监听WebSocket错误。
    //   console.log('WebSocket连接打开失败')
    // })

    // wx.onSocketClose(function (res) { // 监听WebSocket关闭。
    //   setTimeout(function(){
    //     self.reconnectAttempts++;
    //     self.connect(true);
    //   }, self.reconnectInterval)
    // })
  }

  

  sendSocketMessage(param) {
    this.socketTask.send(param)
  }
}

export default Socket;