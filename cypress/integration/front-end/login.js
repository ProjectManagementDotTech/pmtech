describe("Login", function() {
    it("checks the 'Login' button is disabled at first", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("button.btn-primary").should("be.disabled");
    });

    it("does not contain an email field error at first", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email").should("exist");
        cy.get("#vee_email").should("not.exist");
    });

    it("Does not type an email address", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email")
            .type(" ")
            .should("have.value", " ")
            .should("have.class", "border-red-600")
            .should("not.have.class", "border-gray-700");
        cy.get("#vee_email").contains("The email field is required");
        cy.get("button.btn-primary").should("be.disabled");
    });

    it("Types invalid email address", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email")
            .type("something")
            .should("have.value", "something")
            .should("have.class", "border-red-600")
            .should("not.have.class", "border-gray-700");
        cy.get("#vee_email").contains("The email field must be a valid email");
        cy.get("button.btn-primary").should("be.disabled");
    });

    it("Types invalid password", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#password")
            .type(" ")
            .should("have.value", " ")
            .should("have.class", "border-red-600")
            .should("not.have.class", "border-gray-700");
        cy.get("#vee_password").contains("The password field is required");
        cy.get("button.btn-primary").should("be.disabled");
    });

    it("Types valid email address and invalid password", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email")
            .type("some.one@email.com")
            .should("have.value", "some.one@email.com");
        cy.get("#password")
            .type(" ")
            .should("have.value", " ")
            .should("have.class", "border-red-600")
            .should("not.have.class", "border-gray-700");
        cy.get("#vee_password").contains("The password field is required");
        cy.get("button.btn-primary").should("be.disabled");
    });

    it("Types invalid email address and valid password", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email")
            .type("something")
            .should("have.value", "something")
            .should("have.class", "border-red-600")
            .should("not.have.class", "border-gray-700");
        cy.get("#vee_email").contains("The email field must be a valid email");
        cy.get("#password")
            .type("password")
            .should("have.value", "password");
        cy.get("button.btn-primary").should("be.disabled");
    });

    it("Uses incorrect credentials", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email")
            .type("some.one@email.com")
            .should("have.value", "some.one@email.com");
        cy.get("#password")
            .type("Welcome123")
            .should("have.value", "Welcome123");
        cy.get("button.btn-primary").should("be.enabled").click();
    });

    it("Uses correct credentials", function() {
        cy.visit("https://project-management.tech.localdomain/login");
        cy.get("#email")
            .type("php.guus@gmail.com")
            .should("have.value", "php.guus@gmail.com");
    });
});
