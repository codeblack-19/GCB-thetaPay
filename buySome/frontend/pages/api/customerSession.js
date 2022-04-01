
export default async function handler(req, res){
    const user = JSON.parse(window.sessionStorage.getItem('bs_cus'))
    console.log(user);
    res.json({data: user});
}