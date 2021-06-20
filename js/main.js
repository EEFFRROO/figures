window.onload = () => {
    const server = new Server();
    const addFigureBtn = document.getElementById("addFigureBtn");
    const showFiguresBtn = document.getElementById("showFiguresBtn");
    const contentField = document.getElementById("contentField");

    addFigureBtn.onclick = async () => {
        contentField.innerHTML = "";
        let select = createSelectField(["Круг", "Треугольник", "Параллелограмм"]);
        contentField.append(select);
        let addForm = createAddForm(select.value);
        contentField.append(addForm);
        select.onchange = () => {
            contentField.innerHTML = "";
            contentField.append(select);
            addForm = createAddForm(select.value);
            contentField.append(addForm);
        }
    }
    
    showFiguresBtn.onclick = async () => {
        let data = await server.getFigures();
        createFiguresTables(data);
    }

    function createFiguresTables(data) {
        contentField.innerHTML = "";
        let table;
        let tr;
        let td;
        let th;
        data.forEach(element => {
            if (element[0]["figureType"] == "circle") { // Если круг, то создаем таблицу под его данные
                tr = document.createElement("tr");
                td = document.createElement("td");
                th = document.createElement("th");
                table = document.createElement("table");
                th.textContent = "Тип";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Центр(x, y)";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Радиус";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Площадь";
                tr.append(th);
                table.append(tr);

                tr = document.createElement("tr");
                td.textContent = "Круг";
                tr.append(td);
                td = document.createElement("td");
                td.textContent = "(" + element[0]["x"] + ", " + element[0]["y"] + ")";
                tr.append(td);
                td = document.createElement("td");
                td.textContent = calcDistance(element[1]["x"], element[1]["y"], element[0]["x"], element[0]["y"]);
                tr.append(td);
                td = document.createElement("td");
                td.textContent = element[2].toFixed(2);
                tr.append(td);
                table.append(tr);
                contentField.append(table);
            } else {
                tr = document.createElement("tr");
                td = document.createElement("td");
                th = document.createElement("th");
                table = document.createElement("table");
                th.textContent = "Тип";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Точка 1(x, y)";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Точка 2(x, y)";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Точка 3(x, y)";
                tr.append(th);
                th = document.createElement("th");
                th.textContent = "Площадь";
                tr.append(th);
                table.append(tr);

                tr = document.createElement("tr");
                let type;
                if (element[0]["figureType"] == "triangle")
                    type = "Треугольник";
                else  {
                    if (element[4] == "square")
                        type = "Квадрат";
                    else
                        type = "Параллелограмм";
                }
                td.textContent = type;
                tr.append(td);
                td = document.createElement("td");
                td.textContent = "(" + element[0]["x"] + ", " + element[0]["y"] + ")";
                tr.append(td);
                td = document.createElement("td");
                td.textContent = "(" + element[1]["x"] + ", " + element[1]["y"] + ")";
                tr.append(td);
                td = document.createElement("td");
                td.textContent = "(" + element[2]["x"] + ", " + element[2]["y"] + ")";
                tr.append(td);
                td = document.createElement("td");
                td.textContent = element[3].toFixed(2);
                tr.append(td);
                table.append(tr);
                contentField.append(table);
            }
        });
    }

    function calcDistance(x1, y1, x2, y2) {
        return Math.sqrt((x2 - x1) * (x2 - x1) + (y2 - y1) * (y2 - y1));
    }

    function createSelectField(data) {
        let select = document.createElement("select");
        data.forEach(element => {
            let option = document.createElement("option");
            option.innerText = element;
            option.value = element;
            select.append(option);
        });
        return select;
    }

    function createAddForm(type) {
        let addForm = document.createElement("div");
        if (type == "Круг") {
            let centerX = document.createElement("input");
            centerX.placeholder = "Центр X";
            addForm.append(centerX);
            let centerY = document.createElement("input");
            centerY.placeholder = "Центр Y";
            addForm.append(centerY);
            let radius = document.createElement("input");
            radius.placeholder = "Радиус";
            addForm.append(radius);
            let confirmBtn = document.createElement("button");
            confirmBtn.textContent = "Добавить";
            confirmBtn.onclick = () => {
                if (!centerX.value || !centerY.value || !radius.value) {
                    alert("Заполните все поля");
                } else {
                    server.addCircle(centerX.value, centerY.value, centerX.value, centerY.value - radius.value);
                    contentField.innerHTML = "";
                }
            }
            addForm.append(confirmBtn);
        } else {
            let point1X = document.createElement("input");
            point1X.placeholder = "Точка 1 X";
            addForm.append(point1X);
            let point1Y = document.createElement("input");
            point1Y.placeholder = "Точка 1 Y";
            addForm.append(point1Y);
            let point2X = document.createElement("input");
            point2X.placeholder = "Точка 2 X";
            addForm.append(point2X);
            let point2Y = document.createElement("input");
            point2Y.placeholder = "Точка 2 Y";
            addForm.append(point2Y);
            let point3X = document.createElement("input");
            point3X.placeholder = "Точка 3 X";
            addForm.append(point3X);
            let point3Y = document.createElement("input");
            point3Y.placeholder = "Точка 3 Y";
            addForm.append(point3Y);
            let confirmBtn = document.createElement("button");
            confirmBtn.textContent = "Добавить";
            confirmBtn.onclick = () => {
                if (!point1X.value || !point1Y.value || !point2X.value || !point2Y.value || !point3X.value || !point3Y.value) {
                    alert("Заполните все поля");
                } else {
                    if (type == "Треугольник")
                        server.addTriangle(point1X.value, point1Y.value, point2X.value, point2Y.value, point3X.value, point3Y.value);
                    else
                        server.addParallelogram(point1X.value, point1Y.value, point2X.value, point2Y.value, point3X.value, point3Y.value);
                    contentField.innerHTML = "";
                }
            }
            addForm.append(confirmBtn);
        }
        return addForm;
    }
}