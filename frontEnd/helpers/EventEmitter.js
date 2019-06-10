class EventEmitter{
  constructor() {
    this.events = {}
  }

  on = function (eventName, callback) {
    this._addListener(eventName, callback, false)
  };

  _addListener = function (eventName, callback, prepend) {
    this.events[eventName] = this.events[eventName] || [];
    this.events[eventName].push(callback);
  }

  once = function() {
  };

  //触发事件函数
  emit = function (eventName, _) {
    var events = this.events[eventName],
      args = Array.prototype.slice.call(arguments, 1),
      i, m;

    if (!events) {
      return;
    }
    for (i = 0, m = events.length; i < m; i++) {
      events[i].apply(null, args);
    }
  };

}

export default EventEmitter