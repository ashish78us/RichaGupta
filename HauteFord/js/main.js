console.log("starting java script");
$(document).ready( function ($) {

    // Sélecteur JQuery des éléments possédant les classes CSS table-dt OU table-dten, stockant le résultat dans une variable JS (dt)
    // L'instruction let permet de déclarer une variable (ici dt) dont la portée est celle du bloc courant, éventuellement en initialisant sa valeur : https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Statements/let
    let dt = $('.table-dt, .table-dten');
    // console.log permet d'afficher le résultat dans la console du navigateur web (dans ce cas, la console affichera l'objet dt)
    console.log(dt);
    // On applique la fonction DataTable sur notre objet dt (provenant de la lib DataTable)
    dt.dataTable({
        "pageLength": 25
    });
    // Syntaxe alternative courte (sans déclaration de variable, ici en réalité superflue)
    // $('.table-dt, .table-dten').DataTable();

    // Syntaxe alternative de sélection des éléments (sous forme d'arborescence dans le cas de querySelectorAll) possédant les classes CSS table-dt OU table-dten en JS Vanilla
    // il n'est pas possible ici d'appeler la fonction DataTable() sur cet objet, car DataTable requiert JQuery
    let tabledts = document.querySelectorAll('.table-dt, .table-dten');
    console.log(tabledts);
    // Sélection d'un élément unique (le premier rencontré dans le DOM sera sélectionné dans le cas de multiples résultats possibles)
    let tabledt = document.querySelector('.table-dt, .table-dten');
    console.log(tabledt);

    // DataTable peut être paramétré via différentes options : https://datatables.net/manual/options
    // Dans le cas de ce projet, nous allons traduire les éléments d'interface pour les utilisateurs francophones (lang fr, et donc classe CSS table-dtfr)
    $('.table-dtfr').DataTable( {
        language: {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        },
        "pageLength": 25
    });


/**
 * Les événements DOM : https://developer.mozilla.org/fr/docs/Web/Events
 */
    // Dans la suite de ce script, nous allons écrire une fonction permettant de changer la classe CSS du tableau HTML courses-list du projet à partir d'un clic sur le bouton button-courses-list
    // les classes CSS bgblue et bgyellow doivent exister dans notre projet. Pour ce faire, un nouveau fichier css/main.css a été créé. Ce fichier doit être inclu dans la vue header.html

    // Syntaxe JQuery
    // Sélection du bouton et association de l'event "Mouse click" : https://api.jquery.com/click/#click-handler
    // $('#button-courses-list').click(function() {
    //     // Sélection du tableau HTML
    //     let courseslistjq = $('#courses-list');
    //     console.log(courseslistjq);
    //     // Si le tableau HTML possède la classe CSS bgblue
    //     if (courseslistjq.hasClass('bgblue')) {
    //         // On remplace la classe bgblue par bgyellow
    //         courseslistjq.removeClass('bgblue').addClass('bgyellow');
    //     } else {
    //         courseslistjq.removeClass('bgyellow').addClass('bgblue');
    //     }
    // });

    // Syntaxe Javascript Vanilla
    // Sélection du bouton
    let btncl = document.querySelector('#button-courses-list');
    // Ajout de l'événement click au bouton
    if (btncl) {
        btncl.addEventListener('click', event => {
            // sélection des classes CSS du tableau HTML
            let courseslist = document.querySelector('#courses-list').classList;
            if (courseslist.contains('bgblue')) {
                courseslist.replace('bgblue','bgyellow');
            } else {
                courseslist.remove('bgyellow');
                courseslist.add('bgblue');
            }
        });
    }

    // La méthode addEventListener() attache une fonction à appeler chaque fois que l'événement spécifié est envoyé à la cible.
    window.addEventListener("load", function(e) {
        /**
         *  === FETCH : AJAX LIKE ===
         */
        let selector = document.querySelector("#cc-formation");
        if (typeof(selector) != 'undefined' && selector != null) {
            selector.addEventListener("change", function (e) {
                // La méthode preventDefault(), rattachée à l'interface Event, indique à l'agent utilisateur que si l'événement n'est pas explicitement géré, l'action par défaut ne devrait pas être exécutée comme elle l'est normalement.
                e.preventDefault();
                // La réponse du fetch est un objet Promise (pour « promesse »). Il est utilisé pour réaliser des traitements de façon asynchrone.
                // Une promesse représente une valeur qui peut être disponible maintenant, dans le futur voire jamais.
                fetch("api/route/course/getByFormationForForm/" + selector.value, {
                    method: "GET",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                }).then(function (response) {
                    return response.text();
                }).then(function (response) {
                    let courseList = document.querySelector("#cc-course");
                    courseList.innerHTML = response.toString();
                }).catch((error) => {
                    console.log(error.toString());
                });
            });
        }

        /**
         * FETCH : APPEND - INCLUDE
         */

        const includeMenu = document.querySelector(".include-admin-menu");
        if (typeof(includeMenu) != 'undefined' && includeMenu != null) {
            fetch("view/admin/menu.html")
                .then(function (response) {
                    return response.text();
                }).then(function (response) {
                    includeMenu.innerHTML = response.toString();
                }).catch((error) => {
                    console.log(error.toString());
                });
        }

        /**
         * UsersList : Modals
         * @type {NodeListOf<Element>}
         */
        let callerid = document.querySelector("#m-uid");
        let callertoken = document.querySelector("#m-tokenu");
        let modalBody = document.querySelector("#modal-user-list-body");
        let userModalLinks = document.querySelectorAll(".user-modal-link");
        userModalLinks.forEach((link) => {
            if (typeof(link) != 'undefined' && link != null) {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    let td = link.closest('td');
                    let userid = td.previousSibling;
                    modalBody.innerHTML = '';
                    fetch("api/route/user/getUserModalBody/" + userid.textContent + "/" + callerid.textContent + "/" + callertoken.textContent, {
                    }).then(function (response) {
                        return response.text();
                    }).then(function (response) {
                        modalBody.innerHTML = response.toString();
                    }).catch((error) => {
                        console.log(error.toString());
                    });
                });
            }
        });
    });


function confirmDelete($id) {
              if (confirm("Are you sure you want to delete")) {
                document.location = 'index.php?view=api/Formation/delete/ + $id ';

            }
        }
 })

 const search = document.querySelector('.input-group input'),
    table_rows = document.querySelectorAll('tbody tr'),
    table_headings = document.querySelectorAll('thead th');



// 2. Sorting | Ordering data of HTML table

table_headings.forEach((head, i) => {
    let sort_asc = true;
    head.onclick = () => {
        table_headings.forEach(head => head.classList.remove('active'));
        head.classList.add('active');

        document.querySelectorAll('td').forEach(td => td.classList.remove('active'));
        table_rows.forEach(row => {
            row.querySelectorAll('td')[i].classList.add('active');
        })

        head.classList.toggle('asc', sort_asc);
        sort_asc = head.classList.contains('asc') ? false : true;

        sortTable(i, sort_asc);
    }
})


function sortTable(column, sort_asc) {
    [...table_rows].sort((a, b) => {
        let first_row = a.querySelectorAll('td')[column].textContent.toLowerCase(),
            second_row = b.querySelectorAll('td')[column].textContent.toLowerCase();

        return sort_asc ? (first_row < second_row ? 1 : -1) : (first_row < second_row ? -1 : 1);
    })
        .map(sorted_row => document.querySelector('tbody').appendChild(sorted_row));
}

// 3. Converting HTML table to PDF

const pdf_btn = document.querySelector('#toPDF');
const customers_table = document.querySelector('#customers_table');

const toPDF = function (customers_table) {
    const html_code = `
    <link rel="stylesheet" href="style.css">
    <main class="table" >${customers_table.innerHTML}</main>
    `;

    const new_window = window.open();
    new_window.document.write(html_code);

    setTimeout(() => {
        new_window.print();
        new_window.close();
    }, 400);
}

pdf_btn.onclick = () => {
    toPDF(customers_table);
}

// 4. Converting HTML table to JSON

const json_btn = document.querySelector('#toJSON');

const toJSON = function (table) {
    let table_data = [],
        t_head = [],

        t_headings = table.querySelectorAll('th'),
        t_rows = table.querySelectorAll('tbody tr');

    for (let t_heading of t_headings) {
        let actual_head = t_heading.textContent.trim().split(' ');

        t_head.push(actual_head.splice(0, actual_head.length - 1).join(' ').toLowerCase());
    }

    t_rows.forEach(row => {
        const row_object = {},
            t_cells = row.querySelectorAll('td');

        t_cells.forEach((t_cell, cell_index) => {
            const img = t_cell.querySelector('img');
            if (img) {
                row_object['customer image'] = decodeURIComponent(img.src);
            }
            row_object[t_head[cell_index]] = t_cell.textContent.trim();
        })
        table_data.push(row_object);
    })

    return JSON.stringify(table_data, null, 4);
}

json_btn.onclick = () => {
    const json = toJSON(customers_table);
    downloadFile(json, 'json')
}

// 5. Converting HTML table to CSV File

const csv_btn = document.querySelector('#toCSV');

const toCSV = function (table) {
    // Code For SIMPLE TABLE
    // const t_rows = table.querySelectorAll('tr');
    // return [...t_rows].map(row => {
    //     const cells = row.querySelectorAll('th, td');
    //     return [...cells].map(cell => cell.textContent.trim()).join(',');
    // }).join('\n');

    const t_heads = table.querySelectorAll('th'),
        tbody_rows = table.querySelectorAll('tbody tr');

    const headings = [...t_heads].map(head => {
        let actual_head = head.textContent.trim().split(' ');
        return actual_head.splice(0, actual_head.length - 1).join(' ').toLowerCase();
    }).join(',') + ',' + 'image name';

    const table_data = [...tbody_rows].map(row => {
        const cells = row.querySelectorAll('td'),
            img = decodeURIComponent(row.querySelector('img').src),
            data_without_img = [...cells].map(cell => cell.textContent.replace(/,/g, ".").trim()).join(',');

        return data_without_img + ',' + img;
    }).join('\n');

    return headings + '\n' + table_data;
}

csv_btn.onclick = () => {
    const csv = toCSV(customers_table);
    downloadFile(csv, 'csv', 'customer orders');
}

// 6. Converting HTML table to EXCEL File

const excel_btn = document.querySelector('#toEXCEL');

const toExcel = function (table) {
    // Code For SIMPLE TABLE
    // const t_rows = table.querySelectorAll('tr');
    // return [...t_rows].map(row => {
    //     const cells = row.querySelectorAll('th, td');
    //     return [...cells].map(cell => cell.textContent.trim()).join('\t');
    // }).join('\n');

    const t_heads = table.querySelectorAll('th'),
        tbody_rows = table.querySelectorAll('tbody tr');

    const headings = [...t_heads].map(head => {
        let actual_head = head.textContent.trim().split(' ');
        return actual_head.splice(0, actual_head.length - 1).join(' ').toLowerCase();
    }).join('\t') + '\t' + 'image name';

    const table_data = [...tbody_rows].map(row => {
        const cells = row.querySelectorAll('td'),
            img = decodeURIComponent(row.querySelector('img').src),
            data_without_img = [...cells].map(cell => cell.textContent.trim()).join('\t');

        return data_without_img + '\t' + img;
    }).join('\n');

    return headings + '\n' + table_data;
}

excel_btn.onclick = () => {
    const excel = toExcel(customers_table);
    downloadFile(excel, 'excel');
}

const downloadFile = function (data, fileType, fileName = '') {
    const a = document.createElement('a');
    a.download = fileName;
    const mime_types = {
        'json': 'application/json',
        'csv': 'text/csv',
        'excel': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    }
    a.href = `
        data:${mime_types[fileType]};charset=utf-8,${encodeURIComponent(data)}
    `;
    document.body.appendChild(a);
    a.click();
    a.remove();
}
        
