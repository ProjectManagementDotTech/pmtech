describe("Public front-end", function() {
   it("Visits the homepage", function() {
       cy.visit("https://project-management.tech.localdomain");
       cy.contains("How it works");
       cy.contains("Time keeping");
       cy.contains("Version 2020.1");
   });

   it("Clicks 'How it works' link", function () {
       cy.visit("https://project-management.tech.localdomain");
       cy.contains("How it works").click();
       cy.url().should("include", "how-it-works");
   });

   it("Clicks 'Pricing and plans' link", function () {
       cy.visit("https://project-management.tech.localdomain");
       cy.contains("Pricing and plans").click();
       cy.url().should("include", "pricing-and-plans");
   });

    it("Clicks 'Login' link", function () {
        cy.visit("https://project-management.tech.localdomain");
        cy.contains("Login").click();
        cy.url().should("include", "login");
    });

    it("Clicks 'Register' link", function () {
        cy.visit("https://project-management.tech.localdomain");
        cy.contains("Register").click();
        cy.url().should("include", "register");
    });
});
