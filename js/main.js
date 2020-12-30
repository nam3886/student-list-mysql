apiGet();

const modalForm = $("#modalForm"),
	selectorBtnDel = "button.btn-danger.del",
	selectorBtnUpdate = "button.btn-warning.update",
	selectorBtnAdd = "button.btn-primary.add",
	selectorBtnExport = ".btn.btn-primary.export",
	selectorBtnImport = ".btn.btn-primary.import",
	selectorFormImport = "#importFile",
	modal = $("#exampleModal");

let action = "",
	id = "",
	title = "",
	content = "",
	isDisplayModal = false,
	isUpdate = false;

$(document).on("click", selectorBtnDel, function (e) {
	isUpdate = false;
	action = "delete";

	const value = getValue(e.target);

	const con = confirm("Are you sure to delete this item?");

	if (!con) return;

	handleButton(action, value);

	resetVariable();
});

$(document).on("click", selectorBtnAdd, function (e) {
	isUpdate = false;
	action = "add";

	modal.modal("show");
});

$(document).on("click", selectorBtnUpdate, function (e) {
	isUpdate = true;
	action = "update";

	const value = getValue(e.target);

	id = value.id;
	title = value.title;
	content = value.content;

	modal.modal("show");
});

$(document).on("click", selectorBtnExport, function (e) {
	window.open(url + "/excel.php?export=true", "_blank");
});

$(document).on("click", selectorBtnImport, function (e) {
	$(selectorFormImport).slideToggle();
});

$(selectorFormImport).submit(function (e) {
	e.preventDefault();
	var formData = new FormData();
	const file = $(this).find("[name='importFile']")[0].files[0];
	formData.append("file", file);

	callAjax("POST", "/excel.php?import=true", formData, (data) => {
		alert(data);
		apiGet();
	});
});

modalForm.submit(function (e) {
	e.preventDefault();
	const idModal = $(this).find("[name=id]").val();
	const titleModal = $(this).find("[name=title]").val();
	const contentModal = $(this).find("[name=content]").val();

	if (idModal == id && titleModal == title && contentModal == content)
		return alert("Giá trị không có sự thay đổi!!!");

	handleButton(action, idModal, titleModal, contentModal);

	modal.modal("hide");
	resetVariable();
});

modal.on("show.bs.modal", function (event) {
	if (!isUpdate) return;

	const modal = $(this);
	modal.find("[name=id]").val(id);
	modal.find("[name=title]").val(title);
	modal.find("[name=content]").val(content);
});

modal.on("hide.bs.modal", function (event) {
	resetVariable();
});

function getValue(elem) {
	const parent = findParent(elem, "tr");

	if (!parent) return;

	const id = $(parent).find("td:nth-child(1)").text();
	const title = $(parent).find("td:nth-child(2)").text();
	const content = $(parent).find("td:nth-child(3)").text();

	return { id, title, content };
}

function handleButton(action, ...data) {
	if (action === "delete") return apiDelete(...data);
	else if (action === "add") return apiAdd(data);
	return apiUpdate(data);
}

function findParent(elem, selector) {
	while (elem.parentElement) {
		if (elem.parentElement.matches(selector)) return elem.parentElement;

		elem = elem.parentElement;
	}
}

function resetVariable() {
	action = "";
	id = "";
	title = "";
	content = "";
	isDisplayModal = false;
	isUpdate = false;
	modalForm.trigger("reset");
}

function createTable(data) {
	$(document).ready(function () {
		const dataTable = $("#post");
		dataTable.dataTable().fnDestroy();
		dataTable.DataTable({
			data: data,
			columns: [{ data: "id" }, { data: "title" }, { data: "content" }],
			responsive: true,
			scrollY: true,
			scroller: {
				rowHeight: 30,
			},
			columnDefs: [
				{
					targets: [3],
					data: null,
					className: "text-center",
					defaultContent:
						"<button type='button' class='btn btn-warning update text-capitalize mr-1'>edit</button><button type='button' class='btn btn-danger del text-capitalize ml-1'>delete</button>",
				},
				{
					targets: "_all",
					className: "text-center",
				},
			],
		});
	});
}

function apiDelete(data) {
	$.ajax({
		url: url + "/api.php?delete=true",
		method: "post",
		data: { id: data.id },
		success: (data) => {
			alert(data);
			apiGet();
		},
		error: (e) => {
			alert(e.response);
		},
	});
}

function apiAdd(data) {
	$.ajax({
		url: url + "/api.php?add=true",
		method: "post",
		data: { title: data[1], content: data[2] },
		success: (data) => {
			alert(data);
			apiGet();
		},
		error: (e) => {
			alert(e.responseText);
		},
	});
}

function apiUpdate(data) {
	$.ajax({
		url: url + "/api.php?update=true",
		method: "post",
		data: { id: data[0], title: data[1], content: data[2] },
		success: (data) => {
			alert(data);
			apiGet();
		},
		error: (e) => {
			alert(e.responseText);
		},
	});
}

function apiGet() {
	$.ajax({
		url: url + "/api.php?get=true",
		method: "get",
		success: (data) => {
			data = JSON.parse(data);
			createTable(data);
		},
		error: (e) => {
			alert(e.responseText);
		},
	});
}

function callAjax(method, pathUrl, data, callbackSuccess, callbackError) {
	$.ajax({
		url: url + pathUrl,
		data: data,
		method: method,
		contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
		processData: false, // NEEDED, DON'T OMIT THIS
		success: (successData) => {
			if (typeof callbackSuccess !== "function") return;
			callbackSuccess(successData);
		},
		error: (e) => {
			alert(e.responseText);

			if (typeof callbackError !== "function") return;
			callbackError(e);
		},
	});
}
