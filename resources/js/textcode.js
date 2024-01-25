function calculate_number(v, frac) {
    if (frac) {
        return calculate_fraction(v);
    } else {
        return calculate_round(v, 'A');
    }
}

window.calculate_number = calculate_number;

function calculate_fraction(v) {

    if (v == 0) {
        return '0';
    }

    var h1 = 1;
    var h2 = 0;
    var k1 = 0;
    var k2 = 1;
    var b = 1 / v;
    do {
        b = 1 / b;
        var a = Math.floor(b);
        var aux = h1;
        h1 = a * h1 + h2;
        h2 = aux;
        aux = k1;
        k1 = a * k1 + k2;
        k2 = aux;
        b = b - a;
    } while (Math.abs(v - h1 / k1) > v * 0.001); //0.001  = tolerance

    if (k1 == 1) {
        return h1;
    }

    if (k1 > 100) {
        return calculate_round(v, 'A');
    }

    var f1 = 0;
    while (h1 > k1) {
        f1++;
        h1 -= k1;
    }

    var ret = '';
    if (f1 > 0) {
        ret += f1.toString() + ' ';
    }
    ret += '<span class="diagonal-fractions">' + h1.toString() + '/' + k1.toString() + '</span>';
    return ret;

}
window.calculate_fraction = calculate_fraction;

function calculate_round(v, d) {

    if (v == 0) {
        return 0;
    }

    var min = 0;

    if (d == 'A') {
        d = 2;
        if (v > 1)
            d = 1;
        if (v > 10)
            d = 0;
        min = 0.01;
    }

    d = Math.pow(10, d);

    v = Math.round(v * d) / d;

    if (v == 0) {
        v = min;
    }

    var na = v.toString().split('.');
    na[0] = na[0].split('').reverse().join('').match(/.{1,3}/g).join('.').split('').reverse().join('');

    if (na.length > 1)
        return na[0] + ',' + na[1];

    return na[0];

}
window.calculate_round = calculate_round;

function calculate_unit(rule, amount) {
    if (rule.substr(0, 1) != '[') {
        return rule;
    }

    amount = Math.abs(amount);

    var foundunit = null;
    var matches = [...rule.matchAll(/\[([<=>]*)([\.\d]+)\]([^\[]+)/g)];
    for (var k = 0; k < matches.length; k++) {
        var cond = matches[k][1];
        var value = matches[k][2];
        var unit = matches[k][3];

        var cs = false; //smaller
        var ce = false; //equal
        var cg = false; //greater
        if (cond == '') {
            ce = true;
        } else {
            if (cond.indexOf('<') >= 0) {
                cs = true;
            }
            if (cond.indexOf('=') >= 0) {
                ce = true;
            }
            if (cond.indexOf('>') >= 0) {
                cg = true;
            }
        }

        if (ce && amount == value) {
            return unit;
        }
        if (cs && amount < value) {
            foundunit = unit;
        }
        if (cg && amount > value) {
            foundunit = unit;
        }
    }


    if (foundunit != null) {
        return foundunit;
    }


    var p = rule.lastIndexOf(']');
    if (p < 0) {
        return '!!! ' + rule + ' !!!';
    }

    return rule.substr(p + 1);
}
window.calculate_unit = calculate_unit;
