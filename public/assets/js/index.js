/**
 * Ported from "Sketch of Voronoi"

 * Dead Code Preservation: http://mmasomi.ir
 * @see http://en.wikipedia.org/wiki/Fortune's_algorithm
 * C++ reference https://www.cs.hmc.edu/~mbrubeck/voronoi.html
 */
"use strict";
const canvas = {
    init() {
        this.elem = document.querySelector("canvas");
        this.resize();
        window.addEventListener("resize", () => this.resize(), false);
        return this.elem.getContext("2d");
    },
    resize() {
        this.width = this.elem.width = this.elem.offsetWidth;
        this.height = this.elem.height = this.elem.offsetHeight;
        pointer.mag = Math.min(this.width, this.height) * 0.05;
    }
};
const pointer = {
    x: 0.0,
    y: 0.0,
    ox: 0.0,
    oy: 0.0,
    mag: 0.0,
    ms2: 0.0,
    move(e, touch) {
        e.preventDefault();
        const pointer = touch ? e.targetTouches[0] : e;
        this.x = pointer.clientX;
        this.y = pointer.clientY;
    },
    save() {
        const mvx = this.x - this.ox;
        const mvy = this.y - this.oy;
        this.ms2 = Math.sqrt(mvx * mvx + mvy * mvy);
        this.ox = this.x;
        this.oy = this.y;
    },
    init(canvas) {
        canvas.elem.addEventListener("mousemove", e => this.move(e, false), false);
        canvas.elem.addEventListener("touchmove", e => this.move(e, true), false);
    }
};
const ctx = canvas.init();
pointer.init(canvas);

////////////////////////////////////////////////////
class Point {
    constructor() {
        this.init();
    }

    init() {
        const angle = Math.random() * 2 * Math.PI;
        this.x = canvas.width * 0.5 + (Math.random() - 0.5) * 5;
        this.y = canvas.height * 0.5 + (Math.random() - 0.5) * 5;
        this.vx = (Math.random() - 0.5) * Math.cos(angle);
        this.vy = (Math.random() - 0.5) * Math.sin(angle);
    }

    move() {
        if (
            this.x < 0 ||
            this.x > canvas.width ||
            this.y < 0 ||
            this.y > canvas.height
        ) {
            this.init();
            return;
        }
        const dx = this.x - pointer.x;
        const dy = this.y - pointer.y;
        const dist2 = dx * dx + dy * dy;
        const angle = Math.atan2(dy, dx);
        const power = pointer.mag / dist2 * pointer.ms2;
        this.vx += power * Math.cos(angle);
        this.vy += power * Math.sin(angle);
        this.x += this.vx;
        this.y += this.vy;
    }
}

class Arc {
    constructor(p, prev, next) {
        this.p = p;
        this.next = next;
        this.prev = prev;
        this.v0 = null;
        this.v1 = null;
        this.left = null;
        this.right = null;
        this.endP = null;
        this.endX = 0.0;
    }
}

////////////////////////////////////////////////////
const N = 14;
var counter = 0;
const points = [];
// init
for (let i = 0; i < N; ++i) {
    const p = (points[i] = new Point());
}
const intersection = (p0, p1, l, res) => {
    let p = p0, ll = l * l;
    if (p0.x === p1.x) res.y = (p0.y + p1.y) / 2;
    else if (p1.x === l) res.y = p1.y;
    else if (p0.x === l) {
        res.y = p0.y;
        p = p1;
    } else {
        const z0 = 0.5 / (p0.x - l);
        const z1 = 0.5 / (p1.x - l);
        const a = z0 - z1;
        const b = -2 * (p0.y * z0 - p1.y * z1);
        const c = (p0.y * p0.y + p0.x * p0.x - ll) * z0 - (p1.y * p1.y + p1.x * p1.x - ll) * z1;
        res.y = (-b - Math.sqrt(b * b - 4 * a * c)) / (2 * a);
    }
    res.x = (p.x * p.x + (p.y - res.y) * (p.y - res.y) - ll) / (2 * p.x - 2 * l);
    return res;
};
const fortune = () => {
    let root = null;
    let a = null;
    let b = null;
    let c = null;
    let d = null;
    let o = new Point();
    let next = null;
    let eventX = 0;
    let w = points[0].x;
    for (let i = 1; i < N; i++) {
        const p = points[i];
        const x = p.x;
        if (x < w) {
            let j = i;
            while (j > 0 && points[j - 1].x > x) {
                points[j] = points[j - 1];
                j--;
            }
            points[j] = p;
        } else w = x;
    }
    const x0 = points[0].x;
    let i = 0;
    let p = points[0];
    let x = p.x;
    for (; ;) {
        if (a !== null) {
            let circle = false;
            if (a.prev !== null && a.next !== null) {
                const aa = a.prev.p;
                const bb = a.p;
                const cc = a.next.p;
                let A = bb.x - aa.x;
                let B = bb.y - aa.y;
                const C = cc.x - aa.x;
                const D = cc.y - aa.y;
                if (A * D - C * B <= 0) {
                    const E = A * (aa.x + bb.x) + B * (aa.y + bb.y);
                    const F = C * (aa.x + cc.x) + D * (aa.y + cc.y);
                    const G = 2 * (A * (cc.y - bb.y) - B * (cc.x - bb.x));
                    if (G > 0.000000001 || G < -0.000000001) {
                        o.x = (D * E - B * F) / G;
                        o.y = (A * F - C * E) / G;
                        A = aa.x - o.x;
                        B = aa.y - o.y;
                        eventX = o.x + Math.sqrt(A * A + B * B);
                        if (eventX >= w) circle = true;
                    }
                }
            }
            if (a.right !== null) a.right.left = a.left;
            if (a.left !== null) a.left.right = a.right;
            if (a === next) next = a.right;
            if (circle === true) {
                a.endX = eventX;
                if (a.endP !== null) {
                    a.endP.x = o.x;
                    a.endP.y = o.y;
                } else {
                    a.endP = o;
                    o = new Point();
                }
                d = next;
                if (d === null) {
                    next = a;
                } else
                    for (; ;) {
                        if (d.endX >= eventX) {
                            a.left = d.left;
                            if (d.left !== null) d.left.right = a;
                            if (next === d) next = a;
                            a.right = d;
                            d.left = a;
                            break;
                        }
                        if (d.right === null) {
                            d.right = a;
                            a.left = d;
                            a.right = null;
                            break;
                        }
                        d = d.right;
                    }
            }
            if (b !== null) {
                a = b;
                b = null;
                continue;
            }
            if (c !== null) {
                a = c;
                c = null;
                continue;
            }
            a = null;
        }
        if (next !== null && next.endX <= x) {
            a = next;
            next = a.right;
            if (next !== null) next.left = null;
            a.right = null;
            if (a.prev !== null) {
                a.prev.next = a.next;
                a.prev.v1 = a.endP;
            }
            if (a.next !== null) {
                a.next.prev = a.prev;
                a.next.v0 = a.endP;
            }
            if (a.v0 !== null) {
                ctx.moveTo(a.v0.x, a.v0.y);
                ctx.lineTo(a.endP.x, a.endP.y);
            }
            if (a.v1 !== null) {
                ctx.moveTo(a.v1.x, a.v1.y);
                ctx.lineTo(a.endP.x, a.endP.y);
            }
            d = a;
            w = a.endX;
            if (a.prev !== null) {
                b = a.prev;
                a = a.next;
            } else {
                a = a.next;
                b = null;
            }
        } else {
            if (p === null) break;
            if (root === null) {
                root = new Arc(p, null, null);
            } else {
                let z = new Point();
                a = root.next;
                if (a !== null) {
                    while (a.next !== null) {
                        a = a.next;
                        if (a.p.y >= p.y) break;
                    }
                    intersection(a.prev.p, a.p, p.x, z);
                    if (z.y <= p.y) {
                        while (a.next !== null) {
                            a = a.next;
                            intersection(a.prev.p, a.p, p.x, z);
                            if (z.y >= p.y) {
                                a = a.prev;
                                break;
                            }
                        }
                    } else {
                        a = a.prev;
                        while (a.prev !== null) {
                            a = a.prev;
                            intersection(a.p, a.next.p, p.x, z);
                            if (z.y <= p.y) {
                                a = a.next;
                                break;
                            }
                        }
                    }
                } else a = root;
                if (a.next !== null) {
                    b = new Arc(a.p, a, a.next);
                    a.next.prev = b;
                    a.next = b;
                } else {
                    b = new Arc(a.p, a, null);
                    a.next = b;
                }
                a.next.v1 = a.v1;
                z.y = p.y;
                z.x =
                    (a.p.x * a.p.x + (a.p.y - p.y) * (a.p.y - p.y) - p.x * p.x) /
                    (2 * a.p.x - 2 * p.x);
                b = new Arc(p, a, a.next);
                a.next.prev = b;
                a.next = b;
                a = a.next;
                a.prev.v1 = z;
                a.next.v0 = z;
                a.v0 = z;
                a.v1 = z;
                b = a.next;
                a = a.prev;
                c = null;
                w = p.x;
            }
            i++;
            if (i >= N) {
                p = null;
                x = 999999;
            } else {
                p = points[i];
                x = p.x;
            }
        }
    }
};
var tableRef = document.getElementById('table');
const showPoint = (x, y) => {
    counter += 1;
    var newRow = tableRef.insertRow(counter);
    newRow.setAttribute('id', (counter) + '-row');

    var numberCell = newRow.insertCell(0);
    var numberText = document.createTextNode(counter);
    numberCell.appendChild(numberText);

    var XCell = newRow.insertCell(1);
    var XText = document.createTextNode(x);
    XCell.appendChild(XText);

    var YCell = newRow.insertCell(1);
    var YText = document.createTextNode(y);
    YCell.appendChild(YText);

};
const showPointOr = (points) => {

    tableRef.innerHTML = "";
    counter = N + 1;
    for (const point of points) {
        counter -= 1;
        var newRow = tableRef.insertRow(0);
        newRow.setAttribute('id', (counter) + '-row');

        var numberCell = newRow.insertCell(0);
        var numberText = document.createTextNode(counter);
        numberCell.appendChild(numberText);

        var XCell = newRow.insertCell(1);
        var XText = document.createTextNode(point.x.toFixed(6));
        XCell.appendChild(XText);

        var YCell = newRow.insertCell(1);
        var YText = document.createTextNode(point.y.toFixed(6));
        YCell.appendChild(YText);
    }

};

//////////////////////////////////////////////////////
const run = () => {
    requestAnimationFrame(run);
    ctx.fillStyle = "#fcfcfc";
    ctx.strokeStyle = "#6b6b6b";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    for (const point of points) {
        point.move();
        ctx.fillStyle = "#6b6b6b";
        ctx.fillRect(point.x, point.y, 3, 3);
        // showPoint(point.x, point.y);
    }
    showPointOr(points);
    ctx.beginPath();
    fortune();
    ctx.stroke();
    pointer.save();
};
run();
// removeRow();