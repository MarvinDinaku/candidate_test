## TempCRM

L'utente deve essere in grado di autenticarsi. (Nota, l'utente autenticato verrà considerato admin).

* L'utente deve essere in grado di creare Clienti
* L'utente deve essere in grado di creare Ordini ed associarli a Clienti. 
    * Quando un Ordine viene creato, viene automaticamente creato un Contratto associato al Cliente e all'Ordine.
* Durante la creazione e modifica di un Ordine, quest'ultimo potrà essere associato ad uno o più Tags già presenti nel sistema.
* Quando viene cancellato un Ordine, viene cancellato il Contratto
* Quando viene cancellato un Cliente, vengono cancellato tutti gli Ordini e tutti i Contratti appartenenti a quel Cliente.
* Tutte le cancellazioni devono essere recuperabili.

## New Features

Below I will describe all the new features added

1-) Customers now are devided into two sections
    
    a-) Active customers (To do this i added a status field in the database to track the customer status)
    b-) Innactive customers
    
   If a customer is innactive all its orders now automaticalle are set to innactive and if the user is activated again so do the orders
   Customers can also be deleted as I did not remove this feature and all the orders are deleted on cascade 
   
2-) Orders now when created should be associated to a certain customer.
       
   a-) On the customer dropdown selection only the active customers are displayed
   b-) When creating an order also tags can be selected from the Tag Dropdown and also removed when the order is updated.
   c-) If a certain customer is deactivated then the order associated to that customer is also deactivated and it cannot be reactivated if the customer is not enabled first.
       (An error will be displayed for the above case) 
       
3-) I also added validation for the customers form and orders form. Backend Laravel validation
