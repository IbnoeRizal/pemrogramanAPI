const map = Object.freeze({
    'qris' : '.container img'
});
const inputmap = Object.freeze({
    'qris' : 'src'
});


document.addEventListener('DOMContentLoaded',()=>{
    const paymentType = document.getElementById('tipe-pembayaran').innerHTML.trim();
    console.log(paymentType);
    const selector = document.querySelector(map[paymentType]).getAttribute(inputmap[paymentType]);
    console.log(inputmap[paymentType]);
    const elem = document.getElementById('confirmation');

    if(!elem){
        console.warn(`elemen confirmasi hilang ${elem}`);
        return;
    }
    if(!selector){
        console.warn(`tipe payment salah ${paymentType}`)
        return;
    }
    fetch(route.form)
    .then(x=>x.text())
    .then(x=>{
        const parsed = new DOMParser().parseFromString(x, "text/html");
        elem.replaceChildren(...parsed.body.children);
    })
    .catch(err => new Notification("Gagal", { body: err.message }));

})
