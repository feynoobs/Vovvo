const Hello = () => {
    const onClick = () => {
        alert('Hello, Vovvo!')
    }
    const text = 'Hello, Vovvo!'
    return (
        <div>
            <h1 id="buz" style={{ fontSize: '2rem', fontWeight: 'bold' }}>{text}</h1>
            <label htmlFor="buz">Click the text above</label>
        </div>
    ); 
}

export default Hello