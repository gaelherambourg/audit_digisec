window.onload = function () {

    // Cache les éléments adresse
    cacherElement(/recommandation*/, 'recommandation');
}

/**
 * Méthode permettant de trouver toutes les balises passée sur le tagParam, et dont l'id contient
 * le regexParam
 * @param regexpParam - C'est le mot à rechercher
 * @param tagParam - C'est la balise à rechercher
 * @returns - Un tableau avec les éléments trouvés
 */
function getElementsByRegexId(regexpParam, tagParam) {
    // Si aucun nom de balise n'est spécifié, on cherche sur toutes les balises  
    tagParam = (tagParam === undefined) ? '*' : tagParam;
    var elementsTable = new Array();
    for (var i = 0; i < document.getElementsByTagName(tagParam).length; i++) {
        if (document.getElementsByTagName(tagParam)[i].id && document.getElementsByTagName(tagParam)[i].id.match(regexpParam)) {
            elementsTable.push(document.getElementsByTagName(tagParam)[i]);
        }
    }
    return elementsTable;
};

/**
 * Méthode permettant de cacher les éléments commençant par le paramètre et ayant le nomAction
 * Enuite un écouteur est ajouté sur le bouton lié à l'action via la méthode 'ajouterListener'
 * @param {} parametre - C'est le paramètre recherché dans la page. (span commençant par ce mot)
 * @param {*} nomAction - C'est le nom du span qu'il faut cacher
 */
function cacherElement(parametre, nomAction) {
    var nombreSpanAvecParametre = getElementsByRegexId(parametre, "span");
    for (var i = 1; i <= nombreSpanAvecParametre.length; i++) {
        document.getElementById(nomAction + i).style.display = "none";
        ajouterListener(i, nomAction);
    }
};

/**
 * Méthode permettant d'ajouter un listener sur le nom de bouton passé en paramètre
 * @param i - C'est l'itération sur laquelle l'action doit être effectuée
 * @param nomAction - C'est le nom du bouton à écouter (contact ou adresse) 
 */
function ajouterListener(i, nomAction) {
    document.getElementById('chapitre_' + nomAction + i).addEventListener("click", function (e) {
        e.preventDefault();
        if (document.getElementById(nomAction + i).style.display == "none") {
            document.getElementById(nomAction + i).style.display = "block";
        } else {
            document.getElementById(nomAction + i).style.display = "none";
        }
    })
};