use voting;

INSERT INTO polls (title) VALUES ('1st Test Poll Y/N/A');
INSERT INTO polls (title) VALUES ('2nd Test Poll Bob/Joe');
INSERT INTO polls (title) VALUES ('3rd Test Poll Bob/Joe/No Confidence/Abstain');

INSERT INTO poll_options (poll_id, option_text) VALUES (1,'Yes'), (1, 'No'), (1, 'Abstain');
INSERT INTO poll_options (poll_id, option_text) VALUES (2,'Bob'), (2, 'Joe');
INSERT INTO poll_options (poll_id, option_text) VALUES (3,'Bob'), (3, 'Joe'), (3, 'No Confidence'), (3, 'Abstain');

INSERT INTO current_poll (id, poll_id, voting_type) VALUES (1,1, 'once');

INSERT INTO votes (poll_id, option_id) VALUES (1,1), (1,1), (1,1), (1,1), (1,2), (1,2), (1,3);
