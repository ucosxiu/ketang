var r = [ "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "+", "/" ], t = function(r) {
    for (var t = new Array(); r > 0; ) {
        var n = r % 2;
        r = Math.floor(r / 2), t.push(n);
    }
    return t.reverse(), t;
}, n = function(r) {
    for (var t = 0, n = 0, a = r.length - 1; a >= 0; --a) 1 == r[a] && (t += Math.pow(2, n)), 
    ++n;
    return t;
}, a = function(r, t) {
    for (var n = 8 - (r + 1) + 6 * (r - 1) - t.length; --n >= 0; ) t.unshift(0);
    for (var a = [], o = r; --o >= 0; ) a.push(1);
    a.push(0);
    for (var e = 0, c = 8 - (r + 1); e < c; ++e) a.push(t[e]);
    for (var f = 0; f < r - 1; ++f) {
        a.push(1), a.push(0);
        for (var h = 6; --h >= 0; ) a.push(t[e++]);
    }
    return a;
}, o = {
    encode: function(o) {
        for (var e = [], c = [], f = 0, h = o.length; f < h; ++f) {
            var s = o.charCodeAt(f), u = t(s);
            if (s < 128) {
                for (var v = 8 - u.length; --v >= 0; ) u.unshift(0);
                c = c.concat(u);
            } else s >= 128 && s <= 2047 ? c = c.concat(a(2, u)) : s >= 2048 && s <= 65535 ? c = c.concat(a(3, u)) : s >= 65536 && s <= 2097151 ? c = c.concat(a(4, u)) : s >= 2097152 && s <= 67108863 ? c = c.concat(a(5, u)) : s >= 4e6 && s <= 2147483647 && (c = c.concat(a(6, u)));
        }
        for (var l = 0, f = 0, h = c.length; f < h; f += 6) {
            var i = f + 6 - h;
            2 == i ? l = 2 : 4 == i && (l = 4);
            for (var g = l; --g >= 0; ) c.push(0);
            e.push(n(c.slice(f, f + 6)));
        }
        for (var p = "", f = 0, h = e.length; f < h; ++f) p += r[e[f]];
        for (var f = 0, h = l / 2; f < h; ++f) p += "=";
        return p;
    },
    decode: function(a) {
        var o = a.length, e = 0;
        "=" == a.charAt(o - 1) && ("=" == a.charAt(o - 2) ? (e = 4, a = a.substring(0, o - 2)) : (e = 2, 
        a = a.substring(0, o - 1)));
        for (var c = [], f = 0, h = a.length; f < h; ++f) for (var s = a.charAt(f), u = 0, v = r.length; u < v; ++u) if (s == r[u]) {
            var l = t(u), i = l.length;
            if (6 - i > 0) for (var g = 6 - i; g > 0; --g) l.unshift(0);
            c = c.concat(l);
            break;
        }
        e > 0 && (c = c.slice(0, c.length - e));
        for (var p = [], d = [], f = 0, h = c.length; f < h; ) if (0 == c[f]) p = p.concat(n(c.slice(f, f + 8))), 
        f += 8; else {
            for (var A = 0; f < h && 1 == c[f]; ) ++A, ++f;
            for (d = d.concat(c.slice(f + 1, f + 8 - A)), f += 8 - A; A > 1; ) d = d.concat(c.slice(f + 2, f + 8)), 
            f += 8, --A;
            p = p.concat(n(d)), d = [];
        }
        return p;
    }
};

module.exports = o;