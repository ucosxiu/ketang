class teetpl {
  constructor(e) {
    this.open = "{{";
    this.close = "}}";
  }



  render(e, t) {
    return this.parse(e, t)
  }

  parse(e, t) {
    //e []
    //t obj
    var o = this;
      // e = e.replace(/\s+|\r|\t|\n/g, " ").replace(n(this.open + "#");
  }
}

export default teetpl