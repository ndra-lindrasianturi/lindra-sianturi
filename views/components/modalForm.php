<div class="modal fade" id="formModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="dynamicForm" method="POST">
        <!-- Generate CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Judul Modal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body" id="modalFormFields">
          <!-- Form Fields akan dimasukkan dari JS -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="modalFormSubmitBtn">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function openDynamicFormModal({
    title,
    actionUrl,
    fields,
    data = {},
    method = "POST"
  }) {
    const modalTitle = document.getElementById("modalTitle");
    const modalForm = document.getElementById("dynamicForm");
    const modalFormFields = document.getElementById("modalFormFields");

    modalTitle.innerText = title;
    modalForm.action = actionUrl;
    modalForm.method = method;

    modalFormFields.innerHTML = "";

    let currentRow = null; // Track the current row for dual input

    fields.forEach((field, index) => {
      let value = data[field.name] ?? field.value ?? ""; // Prioritize `data` then default `value`

      // If field is hidden, add it directly to the form
      if (field.type === "hidden") {
        let hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = field.name;
        hiddenInput.value = value;
        modalForm.appendChild(hiddenInput);
        return; // Skip rendering visible input
      }

      // Create a new row if `row` is not set or it's the first field
      if (!field.row || !currentRow || (field.row && currentRow.dataset.row !== "true")) {
        currentRow = document.createElement("div");
        currentRow.classList.add("row", "mb-3");
        if (field.row) {
          currentRow.dataset.row = "true"; // Mark this row as a dual-input row
        }
        modalFormFields.appendChild(currentRow);
      }

      let inputWrapper = document.createElement("div");
      inputWrapper.classList.add(field.row ? "col-md-6" : "col-12"); // Use half-width for dual input

      if (field.label) {
        let label = document.createElement("label");
        label.classList.add("form-label");
        label.innerText = field.label;
        inputWrapper.appendChild(label);
      }

      // Select input
      if (field.type === "select") {
        let select = document.createElement("select");
        select.name = field.name;
        select.classList.add("form-select");
        if (field.required) {
          select.required = true;
        }

        field.options.forEach(opt => {
          let option = document.createElement("option");
          option.value = opt.value;
          option.textContent = opt.label;
          if (opt.value === value) {
            option.selected = true;
          }
          select.appendChild(option);
        });

        inputWrapper.appendChild(select);
      } else if (field.type === "textarea") {
        let textarea = document.createElement("textarea");
        textarea.name = field.name;
        textarea.classList.add("form-control");
        textarea.value = value;
        if (field.required) {
          textarea.required = true;
        }
        inputWrapper.appendChild(textarea);
      } else if (field.type === "date") {
        let dateInput = document.createElement("input");
        dateInput.type = "date";
        dateInput.name = field.name;
        dateInput.classList.add("form-control");
        dateInput.value = value;
        if (field.required) {
          dateInput.required = true;
        }
        inputWrapper.appendChild(dateInput);
      } else {
        // Default: text input
        let input = document.createElement("input");
        input.type = field.type || "text";
        input.name = field.name;
        input.classList.add("form-control");
        input.value = value;
        if (field.required) {
          input.required = true;
        }
        inputWrapper.appendChild(input);
      }

      currentRow.appendChild(inputWrapper);

      // Reset row if the next field is not part of the same row or the current row is full
      if (!fields[index + 1] || !fields[index + 1].row || currentRow.childElementCount >= 2) {
        currentRow = null;
      }
    });
  }
</script>