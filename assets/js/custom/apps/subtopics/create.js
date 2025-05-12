"use strict";

document.addEventListener('DOMContentLoaded', function () {
    const ekleButonu = document.getElementById('ekleButonu');
    const silButonu = document.getElementById('silButonu');
    const eklenenDivler = document.getElementById('eklenenDivler');
    // Sil butonunun görünürlüğünü kontrol eden fonksiyon
    function kontrolSilButonuGorunurlugu() {
        if (eklenenDivler.children.length < 3) {
            silButonu.style.display = 'none'; // Veya silButonu.disabled = true;
        } else {
            silButonu.style.display = 'inline-block'; // Veya silButonu.disabled = false;
        }
    }

    // Sayfa yüklendiğinde kontrol et
    kontrolSilButonuGorunurlugu();

    let inputSayaci = 1;

    ekleButonu.addEventListener('click', function () {

        document.getElementById("silButonu").style.display = "inline-block";
        inputSayaci++;
        // 1. Üstteki div'i oluştur
        const ustDiv = document.createElement('div');
        ustDiv.classList.add('mb-3');
        const ustInput = document.createElement('textarea');
        ustInput.classList.add('form-control', 'form-control-solid');
        ustInput.setAttribute('name', `testsoru[]`);
        ustInput.placeholder = 'Soru ' + inputSayaci;
        ustDiv.appendChild(ustInput);
        eklenenDivler.appendChild(ustDiv);

        // 2. Alttaki div'i oluştur (4 sütun)
        const altDiv = document.createElement('div');
        altDiv.classList.add('row', 'mb-7');

        for (let i = 1; i <= 4; i++) {
            const sutunDiv = document.createElement('div');
            sutunDiv.classList.add('col-md-3', 'mb-3');
            const altInput = document.createElement('input');
            altInput.type = 'text';
            altInput.classList.add('form-control', 'form-control-solid');
            if (i == 1) {
                altInput.setAttribute('name', `cevap_a[]`);
                altInput.placeholder = `A Şıkkı`;
            } else if (i == 2) {
                altInput.setAttribute('name', `cevap_b[]`);
                altInput.placeholder = `B Şıkkı`;
            } else if (i == 3) {
                altInput.setAttribute('name', `cevap_c[]`);
                altInput.placeholder = `C Şıkkı`;
            } else if (i == 4) {
                altInput.setAttribute('name', `cevap_d[]`);
                altInput.placeholder = `D Şıkkı`;
            }
            sutunDiv.appendChild(altInput);
            altDiv.appendChild(sutunDiv);
        }

        // 3. Alttaki div'i oluştur (5 sütun)
        const altDiv2 = document.createElement('div');
        altDiv2.classList.add('row', 'mb-7', 'border-bottom');

        const ilkSutunDiv = document.createElement('div');
        ilkSutunDiv.classList.add('col-md-2', 'd-flex', 'align-items-center');

        const textAnswer = document.createElement('span');
        textAnswer.textContent = 'Sorunun cevabı:';
        ilkSutunDiv.appendChild(textAnswer);
        altDiv2.appendChild(ilkSutunDiv);

        for (let i = 2; i <= 5; i++) {
            const sutunDiv2 = document.createElement('div');
            sutunDiv2.classList.add('col-md-2', 'mb-3');
            const altInput2 = document.createElement('input');
            altInput2.type = 'checkbox';
            altInput2.classList.add('form-check-input', 'ms-10', 'me-1', 'soru_' + inputSayaci);
            const label = document.createElement('label');
            if (i == 2) {
                altInput2.setAttribute('name', `testcevap[]`);
                altInput2.setAttribute('value', 'A');
                label.textContent = " A ";
            } else if (i == 3) {
                altInput2.setAttribute('name', `testcevap[]`);
                altInput2.setAttribute('value', 'B');
                label.textContent = " B ";
            } else if (i == 4) {
                altInput2.setAttribute('name', `testcevap[]`);
                altInput2.setAttribute('value', 'C');
                label.textContent = " C ";
            } else if (i == 5) {
                altInput2.setAttribute('name', `testcevap[]`);
                altInput2.setAttribute('value', 'D');
                label.textContent = " D ";
            }
            sutunDiv2.appendChild(altInput2);
            sutunDiv2.appendChild(label);
            altDiv2.appendChild(sutunDiv2);
        }
        eklenenDivler.appendChild(altDiv);
        eklenenDivler.appendChild(altDiv2);
        

        let soruSayisi = 1;

        const allElements = document.querySelectorAll('*');

        allElements.forEach(element => {
            element.classList.forEach(className => {
                if (className.startsWith('soru_')) {
                    soruSayisi++;
                }
            });
        });

        let colNum = (Math.ceil(soruSayisi / 4));

        const myData = {};

        for (let i = 2; i <= colNum-1; i++) {
            myData[`item_${i}`] = document.querySelectorAll(`.soru_${colNum-1}`);
            myData[`item_${i}`].forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        const currentGroupName = this.name;
                        myData[`item_${i}`].forEach(otherCheckbox => {
                            if (otherCheckbox !== this) {
                                otherCheckbox.checked = false;
                            }
                        });
                    }
                });
            });
        }
    });

    silButonu.addEventListener('click', function () {
        const childNodes = eklenenDivler.children;
        const count = childNodes.length;
        inputSayaci--;

        // Eğer en az üç div varsa (üstteki ve alttaki), son üç div'i sil
        if (count >= 3) {
            eklenenDivler.removeChild(childNodes[count - 1]); // Son eklenen (alttaki) div'i sil
            eklenenDivler.removeChild(childNodes[count - 2]); // Ondan önceki (üstteki) div'i sil
            eklenenDivler.removeChild(childNodes[count - 3]); // Ondan önceki (üstteki) div'i sil
            kontrolSilButonuGorunurlugu();
        }
    });

    // Test Bitti, Çözümlü Başladı

    const addQuestion = document.getElementById('addQuestion');
    const deleteQuestion = document.getElementById('deleteQuestion');
    const additionalQuestions = document.getElementById('additionalQuestions');
    // Sil butonunun görünürlüğünü kontrol eden fonksiyon
    function kontroldeleteQuestionGorunurlugu() {
        if (additionalQuestions.children.length < 3) {
            deleteQuestion.style.display = 'none'; // Veya deleteQuestion.disabled = true;
        } else {
            deleteQuestion.style.display = 'inline-block'; // Veya deleteQuestion.disabled = false;
        }
    }

    // Sayfa yüklendiğinde kontrol et
    kontroldeleteQuestionGorunurlugu();

    let inputSayaciQue = 1;

    addQuestion.addEventListener('click', function () {
        document.getElementById("deleteQuestion").style.display = "inline-block";
        inputSayaciQue++;
        // 1. Üstteki div'i oluştur
        const queUstDiv = document.createElement('div');
        queUstDiv.classList.add('mb-3');
        const queUstInput = document.createElement('textarea');
        queUstInput.classList.add('form-control', 'form-control-solid');
        queUstInput.setAttribute('name', `cozumlusoru[]`);
        queUstInput.placeholder = 'Soru ' + inputSayaciQue;
        queUstDiv.appendChild(queUstInput);
        additionalQuestions.appendChild(queUstDiv);

        // 2. Alttaki div'i oluştur (4 sütun)
        const queAltDiv = document.createElement('div');
        queAltDiv.classList.add('row', 'mb-7');

        for (let i = 1; i <= 4; i++) {
            const queSutunDiv = document.createElement('div');
            queSutunDiv.classList.add('col-md-3', 'mb-3');
            const queAltInput = document.createElement('input');
            queAltInput.type = 'text';
            queAltInput.classList.add('form-control', 'form-control-solid');
            if (i == 1) {
                queAltInput.setAttribute('name', `cozumlu_cevap_a[]`);
                queAltInput.placeholder = `A Şıkkı`;
            } else if (i == 2) {
                queAltInput.setAttribute('name', `cozumlu_cevap_b[]`);
                queAltInput.placeholder = `B Şıkkı`;
            } else if (i == 3) {
                queAltInput.setAttribute('name', `cozumlu_cevap_c[]`);
                queAltInput.placeholder = `C Şıkkı`;
            } else if (i == 4) {
                queAltInput.setAttribute('name', `cozumlu_cevap_d[]`);
                queAltInput.placeholder = `D Şıkkı`;
            }
            queSutunDiv.appendChild(queAltInput);
            queAltDiv.appendChild(queSutunDiv);
        }

        // 3. Alttaki div'i oluştur (5 sütun)
        const queAltDiv2 = document.createElement('div');
        queAltDiv2.classList.add('row', 'mb-7');

        const queSlkSutunDiv = document.createElement('div');
        queSlkSutunDiv.classList.add('col-md-2', 'd-flex', 'align-items-center');

        const textAnswer = document.createElement('span');
        textAnswer.textContent = 'Sorunun cevabı:';
        queSlkSutunDiv.appendChild(textAnswer);
        queAltDiv2.appendChild(queSlkSutunDiv);

        for (let i = 2; i <= 5; i++) {
            const queSutunDiv2 = document.createElement('div');
            queSutunDiv2.classList.add('col-md-2', 'mb-3');
            const queAltInput2 = document.createElement('input');
            queAltInput2.type = 'checkbox';
            queAltInput2.classList.add('form-check-input', 'ms-10', 'me-1', 'cozumluSoru_' + inputSayaciQue);
            const label = document.createElement('label');
            if (i == 2) {
                queAltInput2.setAttribute('name', `cozumlu_testcevap[]`);
                queAltInput2.setAttribute('value', 'a');
                label.textContent = " A ";
            } else if (i == 3) {
                queAltInput2.setAttribute('name', `cozumlu_testcevap[]`);
                queAltInput2.setAttribute('value', 'b');
                label.textContent = " B ";
            } else if (i == 4) {
                queAltInput2.setAttribute('name', `cozumlu_testcevap[]`);
                queAltInput2.setAttribute('value', 'c');
                label.textContent = " C ";
            } else if (i == 5) {
                queAltInput2.setAttribute('name', `cozumlu_testcevap[]`);
                queAltInput2.setAttribute('value', 'd');
                label.textContent = " D ";
            }
            queSutunDiv2.appendChild(queAltInput2);
            queSutunDiv2.appendChild(label);
            queAltDiv2.appendChild(queSutunDiv2);
        }

        // 4. Dördüncü satır (Tek kolonlu textarea)
        const answerAltDiv = document.createElement('div');
        answerAltDiv.classList.add('row', 'mb-7', 'border-bottom');

        const answer = document.createElement('div');
        answer.classList.add('mb-3');

        const answerTextarea = document.createElement('textarea');
        answerTextarea.classList.add('form-control', 'form-control-solid');
        answerTextarea.setAttribute('name', `solution[]`);
        /*const textareaLabel = document.createElement('label');
        textareaLabel.classList.add('form-label', 'd-block');
        textareaLabel.textContent = 'Ek Açıklama:';
        answer.appendChild(textareaLabel);*/
        answerTextarea.placeholder = 'Çözüm ';
        answer.appendChild(answerTextarea);
        answerAltDiv.appendChild(answer);

        additionalQuestions.appendChild(queAltDiv);
        additionalQuestions.appendChild(queAltDiv2);
        additionalQuestions.appendChild(answerAltDiv);

        let soruSayisiSolution = 1;

        const allElementsSolution = document.querySelectorAll('*');

        allElementsSolution.forEach(element => {
            element.classList.forEach(className => {
                if (className.startsWith('cozumluSoru_')) {
                    soruSayisiSolution++;
                }
            });
        });

        let Solution = (Math.ceil(soruSayisiSolution / 4));

        const myDataSolution = {};

        for (let i = 2; i <= Solution-1; i++) {
            myDataSolution[`item_${i}`] = document.querySelectorAll(`.cozumluSoru_${Solution-1}`);
            myDataSolution[`item_${i}`].forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        const currentGroupName = this.name;
                        myDataSolution[`item_${i}`].forEach(otherCheckbox => {
                            if (otherCheckbox !== this) {
                                otherCheckbox.checked = false;
                            }
                        });
                    }
                });
            });
        }
    });

    deleteQuestion.addEventListener('click', function () {
        const childNodes = additionalQuestions.children;
        const count = childNodes.length;
        inputSayaciQue--;

        // Eğer en az dört div varsa (üstteki ve alttaki), son dört div'i sil
        if (count >= 4) {
            additionalQuestions.removeChild(childNodes[count - 1]); // Son eklenen (alttaki) div'i sil
            additionalQuestions.removeChild(childNodes[count - 2]); // Ondan önceki (üstteki) div'i sil
            additionalQuestions.removeChild(childNodes[count - 3]); // Ondan önceki (üstteki) div'i sil
            additionalQuestions.removeChild(childNodes[count - 4]); // Ondan önceki (üstteki) div'i sil
            kontroldeleteQuestionGorunurlugu();
        }
    });


    const checkboxes = document.querySelectorAll('.soru_1');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                checkboxes.forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        });
    });

    const checkboxesSolution = document.querySelectorAll('.cozumluSoru_1');

    checkboxesSolution.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                checkboxesSolution.forEach(otherCheckbox => {
                    if (otherCheckbox !== this) {
                        otherCheckbox.checked = false;
                    }
                });
            }
        });
    });
});






/*
// Class definition
var KTAppInvoicesCreate = function () {
    var form;

    // Private functions
    var updateTotal = function() {
        var items = [].slice.call(form.querySelectorAll('[data-kt-element="items"] [data-kt-element="item"]'));
        var grandTotal = 0;

        var format = wNumb({
            //prefix: '$ ',
            decimals: 2,
            thousand: ','
        });

        items.map(function (item) {
            var quantity = item.querySelector('[data-kt-element="quantity"]');
            var price = item.querySelector('[data-kt-element="price"]');

            var priceValue = format.from(price.value);
            priceValue = (!priceValue || priceValue < 0) ? 0 : priceValue;

            var quantityValue = parseInt(quantity.value);
            quantityValue = (!quantityValue || quantityValue < 0) ?  1 : quantityValue;

            price.value = format.to(priceValue);
            quantity.value = quantityValue;

            item.querySelector('[data-kt-element="total"]').innerText = format.to(priceValue * quantityValue);			

            grandTotal += priceValue * quantityValue;
        });

        form.querySelector('[data-kt-element="sub-total"]').innerText = format.to(grandTotal);
        form.querySelector('[data-kt-element="grand-total"]').innerText = format.to(grandTotal);
    }

    var handleEmptyState = function() {
        if (form.querySelectorAll('[data-kt-element="items"] [data-kt-element="item"]').length === 0) {
            var item = form.querySelector('[data-kt-element="empty-template"] tr').cloneNode(true);
            form.querySelector('[data-kt-element="items"] tbody').appendChild(item);
        } else {
            KTUtil.remove(form.querySelector('[data-kt-element="items"] [data-kt-element="empty"]'));
        }
    }

    var handeForm = function (element) {
        // Add item
        form.querySelector('[data-kt-element="items"] [data-kt-element="add-item"]').addEventListener('click', function(e) {
            e.preventDefault();

            var item = form.querySelector('[data-kt-element="item-template"] div').cloneNode(true);

            form.querySelector('[data-kt-element="items"] div').appendChild(item);

            handleEmptyState();
            updateTotal();			
        });

        // Remove item
        KTUtil.on(form, '[data-kt-element="items"] [data-kt-element="remove-item"]', 'click', function(e) {
            e.preventDefault();

            KTUtil.remove(this.closest('[data-kt-element="item"]'));

            handleEmptyState();
            updateTotal();			
        });		

        // Handle price and quantity changes
        KTUtil.on(form, '[data-kt-element="items"] [data-kt-element="quantity"], [data-kt-element="items"] [data-kt-element="price"]', 'change', function(e) {
            e.preventDefault();

            updateTotal();			
        });
    }

    var initForm = function(element) {
        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var invoiceDate = $(form.querySelector('[name="invoice_date"]'));
        invoiceDate.flatpickr({
            enableTime: false,
            dateFormat: "d, M Y",
        });

        // Due date. For more info, please visit the official plugin site: https://flatpickr.js.org/
        var dueDate = $(form.querySelector('[name="invoice_due_date"]'));
        dueDate.flatpickr({
            enableTime: false,
            dateFormat: "d, M Y",
        });
    }

    // Public methods
    return {
        init: function(element) {
            form = document.querySelector('#kt_invoice_form');

            handeForm();
            initForm();
            updateTotal();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppInvoicesCreate.init();
});
*/