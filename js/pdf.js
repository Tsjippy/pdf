
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
    let iframe  = el.querySelector('iframe');
    let height  = await getPdfPageDimensions(iframe.src, 1);

    el.querySelector('div').style.height = `${height}px`;

    iframe.style.height = `${height}px`;
});

document.addEventListener('click', ev =>{
    let target  = ev.target;

    if(target.matches('.pdf-fullscreen')){
        ev.stopImmediatePropagation();

        document.querySelectorAll('.'+target.dataset.target).forEach(el=>el.classList.remove('hidden'));

        // Scroll to top
        window.scrollTo(0,0);
    }
})