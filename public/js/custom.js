const map = Object.freeze({
    'qris' : '.container img'
});
const inputmap = Object.freeze({
    'qris' : 'src'
});

const statusChecker = function (data,resolve,reject) {

    const fun = (resolve,reject) => {

        const x = setTimeout(()=>{
            fetch(route.poll,{
                method : "POST",
                body: JSON.stringify({ "key": route.orderID}),
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': data.token
                },
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then( y => {
                if(y.state === "canceled" || y.state ==="expired")
                    return reject();
                if(y.state === "settlement")
                    return resolve();
                else x();
            })
            .catch(err => reject(err))

        },3000);
    };

    return function (){fun(resolve,reject)};

};

const paySimulation = async function(url ,formData, resolve, reject, {nodeStatus = null} = {}){
    const flag = nodeStatus instanceof HTMLElement;
    const res = await fetch(url, {
        method: "POST",
        body: formData,
        headers: {
        'X-Requested-With': 'XMLHttpRequest', // optional, agar Laravel tahu ini AJAX
        }
    });

    const data = await res.json().catch(() => ({}));
    const next = statusChecker(data,resolve,reject);

    if(flag)nodeStatus.innerText = data.status || `(${res.status})`;

    if (!res.ok) {
        if(flag)nodeStatus.style.color = "red";
        throw new Error(`Gagal ${res.status}`);
    }

    const x = new Promise(next);

    if(flag)nodeStatus.innerText = await x;
};

const qrisPayment = function(elem,selector){
    elem.innerHTML = "<div> Loading ...</div>";
    fetch(route.form)
    .then(x=>x.text())
    .then(x=>{
        const parsed = new DOMParser().parseFromString(x, "text/html");
        elem.replaceChildren(...parsed.body.children);
    })
    .then(()=>{
        const form = elem.firstChild;
        document.getElementById('qrCodeUrl').value = selector;

        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // cegah submit normal


            const nwelement = document.createElement('span');
            elem.replaceChildren(nwelement);
            nwelement.innerText = "Wait...";

            const berhasil = node => node.innerText = "pembayaran berhasil";
            const gagal = node => node.innerHTML = "pembayaran gagal";


            paySimulation(
                form.action,
                new FormData(form),
                () => berhasil(nwelement),
                () => gagal(nwelement),
                {nodeStatus: nwelement}
            );

        });
    })
    .catch(err => alert("Gagal", { body: err.message }));
};


//entry point
document.addEventListener('DOMContentLoaded',()=>{
    const paymentType = document.getElementById('tipe-pembayaran').innerHTML.trim();
    console.log(paymentType);
    const selector = document.querySelector(map[paymentType]).getAttribute(inputmap[paymentType]);
    console.log(inputmap[paymentType]);
    const elem = document.getElementById('confirmation');

    if(!elem){
        console.warn(`elemen konfirmasi hilang ${elem}`);
        return;
    }
    if(!selector){
        console.warn(`tipe payment salah ${paymentType}`)
        return;
    }

    switch (paymentType) {
        case 'qris':
            qrisPayment(elem,selector);
            break;

        default:
            break;
    }


});


