const searchTicket = document.querySelector('#search-ticket')
if (searchTicket) {
  searchTicket.addEventListener('input', async function() {
    const response = await fetch('../../src/controllers/action_search_tickets.php?search=' + this.value)
    const tickets = await response.json()
    console.log(tickets)
    const section = document.querySelector('#all-tickets')
    section.innerHTML = ''

    for (const ticket of tickets) {
        const link = document.createElement('a')
        link.href = '../views/ticket.php?id=' + ticket.id 
        
        const article = document.createElement('article')
        article.classList.add('card')
        article.classList.add('ticketCard')
  
        const heading = document.createElement('h4')
        heading.classList.add('ticket-title')
        heading.textContent = ticket.title
        link.appendChild(article)
        article.appendChild(heading)
        section.appendChild(link)
    }
  })
}

      
            