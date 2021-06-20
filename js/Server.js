class Server {

    constructor() {

    }

    async send(method, data) {
        const arr = [];
        for (let key in data) {
            arr.push(`${key}=${data[key]}`);
        }
        if (this.token) {
            arr.push(`&token=${this.token}`);
        }
        const response = await fetch(`api/?method=${method}&${arr.join('&')}`);
        const answer = await response.json();
        if (answer && answer.result === 'ok') {
            return answer.data;
        } else if(answer && answer.result === 'error') {
            return false;
        }
    }

    async getFigures() {
        return await this.send("getFigures");
    }

    addCircle(xc, yc, xr, yr) {
        this.send("addCircle", { xc, yc, xr, yr });
    }

    addTriangle(x1, y1, x2, y2, x3, y3) {
        this.send("addTriangle", { x1, y1, x2, y2, x3, y3 });
    }

    addParallelogram(x1, y1, x2, y2, x3, y3) {
        this.send("addParallelogram", { x1, y1, x2, y2, x3, y3 });
    }

}