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

            // ambil data form sebagai FormData
            const formData = new FormData(form);

            const nwelement = document.createElement('span');
            elem.replaceChildren(nwelement);
            nwelement.innerText = "Wait...";
            //console.error(`action = ${form.action}\n method = ${form.method.toUpperCase()}`);


            try {
                const res = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                    'X-Requested-With': 'XMLHttpRequest', // optional, agar Laravel tahu ini AJAX
                    }
                });

                const data = await res.json().catch(() => ({}));

                nwelement.innerText = data.status || `(${res.status})`;

                if (!res.ok) {
                    nwelement.style.color = "red";
                    throw new Error(`Gagal ${res.status}`);
                }

                const x = new Promise((resolve, reject)=>{
                    let p = () => setTimeout(()=>{

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
                                return reject("pembayaran gagal");
                            if(y.state === "settlement")
                                return resolve("pembayaran berhasil");
                            else p();
                        })
                        .catch(err => reject(err));
                    },3000);
                    p();
                });

                nwelement.innerText = await x;

            } catch (err) {
                console.error('Terjadi error:', err);
            }
        });
    })
    .catch(err => alert("Gagal", { body: err.message }));

})
