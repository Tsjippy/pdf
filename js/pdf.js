
async function getPdfPageDimensions(pdfUrl, pageNumber = 1) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.mjs';
    try {
        const pdf = await pdfjsLib.getDocument(pdfUrl).promise;

        const page = await pdf.getPage(pageNumber);

        //const width = page.view[2];
        const height = page.view[3];

        //console.log(`Page ${1} dimensions: Width = ${width} PDF units, Height = ${height} PDF units`);

        return height * 1.44;

    } catch (error) {
        console.error("Error loading or processing PDF:", error);
        return false;
    }
}

console.log('pdf.js loaded');

document.querySelectorAll('.full-screen-pdf-wrapper').forEach( async el => {
    let object  = el.querySelector('object');
    let height  = await getPdfPageDimensions(object.data, 1);

    el.querySelector('div').style.height = `${height}px`;

    object.style.height = `${height}px`;
});
