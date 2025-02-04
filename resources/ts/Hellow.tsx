import React, { JSX } from "react"

const Container = (props: {title: string, children: React.ReactElement}) : JSX.Element => {
    const { title, children } = props

    return (
        <div style={{ background: 'red' }}>
            <span>{ title }</span>
            <div>{ children }</div>
        </div>
    )
}


const Hello = (props: {}) : JSX.Element => {
    return (
        <Container title="Hellow">
            <p>aaa</p>
        </Container>
    )
}

export default Hello
