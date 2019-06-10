import teetpl from 'teetpl.js'

class teemo {
  constructor() {
  }

  use(e) {
    e = "string" == typeof e ? [e] : e;
    e.forEach(function (value, index) {
      // eval('new '+value)
    })
  }
}

export default teemo