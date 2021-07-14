!function (n, e) {
    function t(n, e, t) {
        return n.getAttribute(e) || t
    }

    function o(n) {
        return e.getElementsByTagName(n)
    }

    function a() {
        var n = e.currentScript;
        return {
            c: t(n, "color", "0,0,0"),
            n: t(n, "count", 99)
        }
    }

    function i() {
        u = x.width = n.innerWidth, c = x.height = n.innerHeight
    }

    function r() {
        m.clearRect(0, 0, u, c);
        var n, e, t, o, a, i;
        s.forEach(function (r, x) {
            for (r.x += r.xa, r.y += r.ya, r.xa *= r.x > u || r.x < 0 ? -1 : 1, r.ya *= r.y > c || r.y < 0 ?
                -1 : 1, m.fillRect(r.x - .5, r.y - .5, 1, 1), e = x + 1; e < l.length; e++) n = l[e],
            null !== n.x && null !== n.y && (o = r.x - n.x, a = r.y - n.y, i = o * o + a * a, i < n
                .max && (n === d && i >= n.max / 2 && (r.x -= .03 * o, r.y -= .03 * a), t = (n.max -
                i) / n.max, m.beginPath(), m.lineWidth = t / 2, m.strokeStyle = "rgba(" + y.c +
                "," + (t + .2) + ")", m.moveTo(r.x, r.y), m.lineTo(n.x, n.y), m.stroke()))
        }), f(r)
    }

    var u, c, l, x = e.createElement("canvas"),
        y = a(),
        m = x.getContext("2d"),
        f = n.requestAnimationFrame || function (e) {
            n.setTimeout(e, 1e3 / 60)
        },
        h = Math.random,
        d = {
            x: null,
            y: null,
            max: 2e4
        };
    o("body")[0].appendChild(x), i(), n.onresize = i, n.onmousemove = function (e) {
        e = e || n.event, d.x = e.clientX, d.y = e.clientY
    }, n.onmouseout = function () {
        d.x = null, d.y = null
    };
    for (var s = [], g = 0; y.n > g; g++) {
        var v = h() * u,
            b = h() * c,
            p = 2 * h() - 1,
            T = 2 * h() - 1;
        s.push({
            x: v,
            y: b,
            xa: p,
            ya: T,
            max: 6e3
        })
    }
    l = s.concat([d]), f(r)
}(window, document)