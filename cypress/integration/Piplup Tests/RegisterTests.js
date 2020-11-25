context('Actions', () => {
  beforeEach(() => {
    cy.visit('http://localhost/school/CS%20160/joshuabranch/cs-160-team-piplup/PogRegister.php')
  })
  
  // User DNE, new user
  it('.type() - Test username functionality', () => {
    cy.get('.action-username').type('foobar123', { delay: 50 }).should('have.value', 'foobar123')
    cy.get('.button-action').click()
    cy.get('span > pre').contains('Please fill out a password.')
  })
  
  // Successfully add user and login
  it('.type() - Test all fields filled functionality', () => {
  	cy.get('.action-username').type('foobar123', { delay: 50 }).should('have.value', 'foobar123')
  	cy.get('.action-pass').type('foopass321', { delay: 50 }).should('have.value', 'foopass321')
  	cy.get('.action-reppass').type('foopass321', { delay: 50 }).should('have.value', 'foopass321');
  	cy.get('.action-email').type('foo@foobar.com', { delay: 50 }).should('have.value', 'foo@foobar.com')
  	cy.get('.button-action').click()
  	cy.get('[value="Login"]').contains('Login')
  })
  
  // Test user login after account creation
  it('.type() - Test login of user added', () => {
  	cy.visit('http://localhost/school/CS%20160/joshuabranch/cs-160-team-piplup/PogLogin.php')
  	cy.get('.field-username').type('foobar123', { delay: 25 }).should('have.value', 'foobar123')
  	cy.get('.field-pass').type('foopass321', { delay: 25}).should('have.value', 'foopass321')
  	cy.get('.button-login').click()
  	cy.get(':nth-child(2) > a').contains('Upload')
  })
})
